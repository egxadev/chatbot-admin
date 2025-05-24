<?php

namespace App\Services;

use App\Models\KnowledgeBase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KnowledgeBaseService
{
    private const DEFAULT_PER_PAGE = 10;
    private const DEFAULT_SORT_BY = 'title';
    private const DEFAULT_SORT_DIR = 'asc';
    private const FILTERABLE_COLUMNS = ['title', 'content', 'status', 'created_at'];

    /**
     * Get paginated knowledge bases with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getPaginatedKnowledgeBases(array $filters): array
    {
        $perPage = (int) ($filters['per_page'] ?? self::DEFAULT_PER_PAGE);
        $page = (int) ($filters['page'] ?? 1);
        $sortBy  = in_array($sort = $filters['sort_by'] ?? self::DEFAULT_SORT_BY, self::FILTERABLE_COLUMNS) ? $sort : self::DEFAULT_SORT_BY;
        $sortDir = in_array($dir = strtolower($filters['sort_dir'] ?? self::DEFAULT_SORT_DIR), ['asc', 'desc']) ? $dir : self::DEFAULT_SORT_DIR;

        $search = trim($filters['search'] ?? '');

        $query = KnowledgeBase::query();

        $query->when($search, function ($query) use ($search) {
            $query->where(function ($subQuery) use ($search) {
                foreach (self::FILTERABLE_COLUMNS as $column) {
                    $subQuery->orWhere($column, 'like', "%{$search}%");
                }
            });
        });

        $query->orderBy($sortBy, $sortDir);

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return [
            'data'  => $data->items(),
            'meta'  => [
                'current_page'  => $data->currentPage(),
                'last_page'     => $data->lastPage(),
                'per_page'      => $data->perPage(),
                'total'         => $data->total(),
                'from'          => $data->firstItem(),
                'to'            => $data->lastItem(),
            ],
            'filters' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_dir' => $sortDir,
            ]
        ];
    }

    /**
     * Create new knowledge base.
     *
     * @param array $data
     * @return KnowledgeBase
     */
    public function createKnowledgeBase(array $data): array
    {
        try {
            $knowledgeBase = \DB::transaction(function () use ($data) {
                $knowledgeBase = KnowledgeBase::create([
                    'title'         => $data['title'],
                    'content'       => $data['content'],
                    'status'        => $data['status'],
                    'created_by'    => auth()->id(),
                ]);

                return $knowledgeBase;
            });

            return [
                'success'       => true,
                'message'       => 'Knowledge base created successfully.',
                'knowledgeBase' => $knowledgeBase,
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to create knowledge base: ' . $e->getMessage());
            return [
                'success'   => false,
                'message'   => 'Failed to create knowledge base.',
            ];
        }
    }

    /**
     * Update knowledge base data.
     *
     * @param KnowledgeBase $knowledgeBase
     * @param array $data
     * @return KnowledgeBase
     */
    public function updateKnowledgeBase(KnowledgeBase $knowledgeBase, array $data): array
    {
        try {
            $updateData = [
                'title'         => $data['title'],
                'content'       => $data['content'],
                'status'        => $data['status'],
                'updated_by'    => auth()->id(),
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = bcrypt($data['password']);
            }

            \DB::transaction(function () use ($knowledgeBase, $updateData, $data) {
                $knowledgeBase->update($updateData);
            });

            return [
                'success'   => true,
                'message'   => 'Knowledge base updated successfully.',
                'data'      => $knowledgeBase->fresh(),
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success'   => false,
                'message'   => 'Knowledge base not found.',
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to update knowledge base: ' . $e->getMessage());
            return [
                'success'   => false,
                'message'   => 'Failed to update knowledge base.',
            ];
        }
    }

    /**
     * Delete knowledge base by ID.
     *
     * @param string $id
     * @return array
     */
    public function deleteKnowledgeBase(string $id): array
    {
        try {
            $knowledgeBase = KnowledgeBase::findOrFail($id);

            return \DB::transaction(function () use ($knowledgeBase) {
                $knowledgeBase->update(['deleted_by' => auth()->id()]);
                $knowledgeBase->delete();

                return [
                    'success'   => true,
                    'message'   => 'Knowledge base deleted successfully.'
                ];
            });
        } catch (ModelNotFoundException $e) {
            return [
                'success'   => false,
                'message'   => 'Knowledge base not found.'
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to delete knowledge base: ' . $e->getMessage());
            return [
                'success'   => false,
                'message'   => 'Failed to delete knowledge base.'
            ];
        }
    }
}
