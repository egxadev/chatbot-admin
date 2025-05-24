<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ChatHistoryService;

class ChatHistoryController extends Controller
{
    protected $chatHistoryService;

    public function __construct(ChatHistoryService $chatHistoryService)
    {
        $this->chatHistoryService = $chatHistoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'title' => 'Chat History',
                'href' => route('chat-histories.index')
            ]
        ];

        $data = $this->chatHistoryService->getPaginatedChatHistories($request->all());

        return inertia('chat-histories/index', array_merge(
            ['breadcrumbs' => $breadcrumbs],
            $data
        ));
    }
}
