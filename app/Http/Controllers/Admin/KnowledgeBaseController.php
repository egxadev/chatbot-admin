<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use App\Http\Controllers\Controller;
use App\Services\KnowledgeBaseService;
use App\Http\Requests\Admin\KnowledgeBaseRequest;

class KnowledgeBaseController extends Controller
{
    protected $knowledgeBaseService;

    public function __construct(KnowledgeBaseService $knowledgeBaseService)
    {
        $this->knowledgeBaseService = $knowledgeBaseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            [
                'title' => 'Knowledge Base',
                'href' => route('knowledge-bases.index')
            ]
        ];

        $data = $this->knowledgeBaseService->getPaginatedKnowledgeBases($request->all());

        return inertia('knowledge-bases/index', array_merge(
            ['breadcrumbs' => $breadcrumbs],
            $data
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            [
                'title' => 'Knowledge Base',
                'href' => route('knowledge-bases.index')
            ],
            [
                'title' => 'Create',
                'href' => route('knowledge-bases.create')
            ]
        ];

        return inertia('knowledge-bases/create', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KnowledgeBaseRequest $request)
    {
        $response = $this->knowledgeBaseService->createKnowledgeBase($request->validated());

        if ($response['success']) {
            return redirect()->route('knowledge-bases.index')->with('success', $response['message']);
        } else {
            return redirect()->route('knowledge-bases.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $breadcrumbs = [
            [
                'title' => 'Knowledge Base',
                'href' => route('knowledge-bases.index')
            ],
            [
                'title' => 'Edit',
                'href' => route('knowledge-bases.edit', $id)
            ]
        ];

        return inertia('knowledge-bases/edit', [
            'breadcrumbs' => $breadcrumbs,
            'knowledgeBase' => KnowledgeBase::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KnowledgeBaseRequest $request, KnowledgeBase $knowledgeBase)
    {
        $response = $this->knowledgeBaseService->updateKnowledgeBase($knowledgeBase, $request->validated());

        if ($response['success']) {
            return redirect()->route('knowledge-bases.index')->with('success', $response['message']);
        } else {
            return redirect()->route('knowledge-bases.index')->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->knowledgeBaseService->deleteKnowledgeBase($id);

        if ($response['success']) {
            return redirect()->route('knowledge-bases.index')->with('success', $response['message']);
        } else {
            return redirect()->route('knowledge-bases.index')->with('error', $response['message']);
        }
    }
}
