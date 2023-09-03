<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketCodeResource;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCode;
use App\Models\User;
use App\Models\WaitingMember;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new TicketCollection(Ticket::with(['user', 'event'])->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $ticket = Ticket::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'quantity_available' => $request->quantity_available,
                'quantity_sold' => $request->quantity_sold,
                'quantity_attended' => $request->quantity_attended,
                'start_sale_date' => $request->start_sale_date,
                'end_sale_date' => $request->end_sale_date,
                'is_hidden' => $request->is_hidden,
                'user_id' => $request->user_id,
                'event_id' => $request->event_id
            ]);
            return new TicketResource($ticket);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Ticket Creation Failed',
                'error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket != null)
            return new TicketResource($ticket);
        else
            return response()->json([
                'message' => 'Ticket Not Found'
            ], 404);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $ticket = Ticket::find($id);
            if ($ticket != null) {
                $this->authorize('update', $ticket);
                $ticket->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'price' => $request->price,
                    'quantity_available' => $request->quantity_available,
                    'quantity_sold' => $request->quantity_sold,
                    'quantity_attended' => $request->quantity_attended,
                    'start_sale_date' => $request->start_sale_date,
                    'end_sale_date' => $request->end_sale_date,
                    'is_hidden' => $request->is_hidden,
                    'user_id' => $request->user_id,
                    'event_id' => $request->event_id
                ]);
                return new TicketResource($ticket);
            } else {
                return response()->json([
                    'message' => "Ticket Not Found"
                ], 404);
            }
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Update ticket failed',
                'error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket != null) {
            $this->authorize('destroy', $ticket);
            $ticket->delete();
            return response()->json([
                'message' => 'Ticket Deleted'
            ]);
        } else {
            return response()->json([
                'message' => 'Ticket is already deleted or it isn\'t exist'
            ], 404);
        }
    }


    /**
     * @throws Exception
     */
    public function purchase()
    {
        //- check the availability
        //- deduct from the available quantity
        //- if the purchase process is successful
        //- create a `TicketCode` entry that has user id, ticket id, and unique generated code [it will be sent to the user as a QR code
        $ticket = Ticket::find(\request('ticket_id'));
        $this->authorize('attend', $ticket);
        $available = $ticket->quantity_available > 0;
        if ($available) {
            $ticket->update([
                'quantity_sold' => $ticket->quantity_sold + 1,
                'quantity_available' => $ticket->quantity_available - 1,
            ]);
            //Payment Logic
            $uniqueTicketCode = bin2hex(random_bytes(10)) . '-' . $ticket->id;
            $newTicketCode = TicketCode::create([
                'code' => $uniqueTicketCode,
                'user_id' => \request('user_id'),
                'ticket_id' => \request('ticket_id'),
            ]);
            //todo: send an email with the ticket and event details
            return response()->json([
                'message' => 'Your ticket has been purchased Please, check your email',
                'ticket' => new TicketCodeResource($newTicketCode)
            ]);
        } else {
            //todo: prevent multiple waiting for the same user
            $waitingCode = substr(md5(time()), 0, 5);
            $waitingMember = WaitingMember::create([
                'code' => $waitingCode,
                'user_id' => \request('user_id'),
                'ticket_id' => \request('ticket_id'),
                'ordered_at' => now()
            ]);
            return response()->json([
                'message' => 'Sorry, there is no available tickets so, you are added to the waiting list',
                'member' => $waitingMember
            ]);
        }
    }


    public function attend()
    {
        $ticket = TicketCode::find(\request('code'));
        if ($ticket == null) {
            return response()->json([
                'message' => 'The Ticket with code is not found'
            ]);
        } else {
            $this->authorize('attend', $ticket);
            if ($ticket->attended_at == null) {

                $ticket->ticket->update([
                    'quantity_attended' => $ticket->ticket->quantity_attended + 1
                ]);

                $ticket->update([
                    'attended_at' => Carbon::now()
                ]);

                return response()->json([
                    'message' => $ticket->user->name . ', You are checked in successfully'
                ]);
            } else {
                return response()->json([
                    'message' => 'This Ticked has been checked in at ' . $ticket->attended_at
                ]);
            }
        }
    }

    public function toggleGoingOnSale()
    {
        $ticket = Ticket::find(\request('ticket_id'));
        if ($ticket != null) {
            $this->authorize('organizer', $ticket);
            $ticket->update([
                'is_hidden' => false,
                'on_sale' => !$ticket->on_sale
            ]);
            return response()->json([
                'message' => $ticket->on_sale ? 'The Ticket is now on sale' : 'The Ticket is not available for sale',
                'ticket' => new TicketResource($ticket)
            ]);
        } else {

            return response()->json([
                'message' => 'Ticket Not Found'
            ]);
        }

    }

    public function toggleShow()
    {
        $ticket = Ticket::find(request('ticket_id'));
        if ($ticket != null) {
            $this->authorize('organizer', $ticket);
            $ticket->update([
                'is_hidden' => !$ticket->is_hidden,
                'on_sale' => false
            ]);

            return response()->json([
                'message' => $ticket->is_hidden ? 'The Ticket is hidden' : 'The Ticket is displayed',
                'ticket' => new TicketResource($ticket)
            ]);
        } else {

            return response()->json([
                'message' => 'Ticket Not Found'
            ]);
        }
    }

    public function cancel()
    {
        $ticket = TicketCode::find(request('code'));
        if ($ticket == null)
            return response()->json([
                'message' => 'Ticket Not Found'
            ]);

        $this->authorize('cancel',$ticket);
        if ($ticket->attended_at != null)
            return response()->json([
                'message' => 'The Ticket can\'t be cancelled, It is already checked in'
            ]);

        //todo: take an action if it is purchased, return the purchase or something
        if ($ticket->purchased)
            return response()->json([
                'message' => 'The Ticket is purchased'
            ]);

        $ticket->ticket->update([
            'quantity_available' => $ticket->ticket->quantity_available + 1,
            'quantity_sold' => $ticket->ticket->quantity_sold - 1,
        ]);

        $ticket->delete();

        return response()->json([
            'message' => 'Your Ticket has been cancelled'
        ]);
    }
}
