<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Get all comments for an announcement with nested replies
     */
    public function index(Announcement $announcement)
    {
        try {
            // Get only parent comments with nested replies
            $comments = Comment::forAnnouncement($announcement->id)
                ->parentOnly()
                ->with(['user:id,name,email', 'replies.user:id,name,email'])
                ->latest()
                ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Comments retrieved successfully',
                'data' => $comments->items(),
                'pagination' => [
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve comments',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a new comment or reply
     */
    public function store(Request $request, Announcement $announcement)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
                'parent_id' => 'nullable|exists:comments,id',
            ]);

            // If parent_id is provided, verify it belongs to same announcement
            if (isset($validated['parent_id'])) {
                $parentComment = Comment::findOrFail($validated['parent_id']);
                if ($parentComment->announcement_id != $announcement->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Parent comment does not belong to this announcement',
                    ], 422);
                }
            }

            // Create comment
            $comment = Comment::create([
                'announcement_id' => $announcement->id,
                'user_id' => Auth::id(),
                'parent_id' => $validated['parent_id'] ?? null,
                'content' => $validated['content'],
            ]);

            // Load relationships
            $comment->load(['user:id,name,email', 'parent']);

            return response()->json([
                'success' => true,
                'message' => $validated['parent_id'] ? 'Reply added successfully' : 'Comment added successfully',
                'data' => $comment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single comment with its replies
     */
    public function show(Comment $comment)
    {
        try {
            $comment->load(['user:id,name,email', 'replies.user:id,name,email', 'parent']);

            return response()->json([
                'success' => true,
                'data' => $comment,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ], 404);
        }
    }

    /**
     * Update a comment (only by the owner)
     */
    public function update(Request $request, Comment $comment)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            // Check if user owns the comment
            if ($comment->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this comment',
                ], 403);
            }

            // Update comment
            $comment->update([
                'content' => $validated['content'],
            ]);

            $comment->load(['user:id,name,email', 'parent']);

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'data' => $comment,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a comment (only by the owner)
     * All comments show deletion message instead of being removed
     */
    public function destroy(Comment $comment)
    {
        try {
            // Check if user owns the comment
            if ($comment->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this comment',
                ], 403);
            }

            // Mark comment as deleted (soft delete for all comments)
            $comment->update([
                'is_deleted' => true,
                'content' => '[deleted]',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
