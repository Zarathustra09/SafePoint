@extends('layouts.admin.app')

@section('content')
    <!-- Moment.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <style>
        .comment-item {
            transition: background-color 0.2s;
        }
        .comment-item:hover {
            background-color: #f8f9fa;
        }
        .comment-actions button {
            font-size: 0.875rem;
        }
        .reply-form, .edit-form {
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>{{ $announcement->title }}</h4>
                            <small class="text-muted">Created {{ $announcement->created_at->format('M d, Y') }} by {{ $announcement->user->name ?? 'Unknown' }}</small>
                        </div>
                        <div>
                            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Back to List
                            </a>
                            <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-primary">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class='bx bx-trash'></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Images Carousel -->
                            @if(!empty($announcement->images) && count($announcement->images) > 0)
                                <div class="col-lg-6 mb-4">
                                    <div id="announcementCarousel" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($announcement->images as $index => $image)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $image['path']) }}"
                                                        class="d-block w-100"
                                                        alt="Announcement Image"
                                                        style="height: 400px; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if(count($announcement->images) > 1)
                                            <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#announcementCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Image Thumbnails -->
                                    @if(count($announcement->images) > 1)
                                        <div class="d-flex flex-wrap mt-2">
                                            @foreach($announcement->images as $index => $image)
                                                <div class="me-2 mb-2" style="width: 60px; height: 60px;">
                                                    <img src="{{ asset('storage/' . $image['path']) }}"
                                                        class="img-thumbnail w-100 h-100"
                                                        style="object-fit: cover; cursor: pointer;"
                                                        onclick="document.querySelector('#announcementCarousel').querySelector('.carousel-item:nth-child({{ $index + 1 }})').classList.add('active');"
                                                        alt="Thumbnail">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Announcement Details -->
                            <div class="{{ !empty($announcement->images) && count($announcement->images) > 0 ? 'col-lg-6' : 'col-12' }}">
                                <div class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }} mb-3">
                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                </div>

                                <h5 class="mb-3">Description</h5>
                                <div class="mb-4">
                                    {{ $announcement->description }}
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Created:</strong> {{ $announcement->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Last Updated:</strong> {{ $announcement->updated_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class='bx bx-comment-dots'></i> Comments</h5>
                    </div>
                    <div class="card-body">
                        <!-- Comment Form -->
                        <form id="commentForm" class="mb-4">
                            <div class="mb-3">
                                <label for="commentContent" class="form-label">Add a comment</label>
                                <textarea class="form-control" id="commentContent" rows="3" placeholder="Write your comment here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-send'></i> Post Comment
                            </button>
                        </form>

                        <hr>

                        <!-- Comments List -->
                        <div id="commentsList">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading comments...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const announcementId = {{ $announcement->id }};
        let currentUser = null;

        // Load comments on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadComments();

            // Submit comment form
            document.getElementById('commentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                postComment();
            });
        });

        // Load comments from API
        async function loadComments() {
            try {
                const response = await fetch(`/api/announcements/${announcementId}/comments`, {
                    headers: {
                        'Authorization': 'Bearer ' + getAuthToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to load comments');

                const data = await response.json();
                displayComments(data.data);
            } catch (error) {
                document.getElementById('commentsList').innerHTML = '<p class="text-danger">Failed to load comments. Please try again.</p>';
            }
        }

        // Post a new comment
        async function postComment(parentId = null) {
            const content = document.getElementById('commentContent').value.trim();
            if (!content) return;

            const submitBtn = document.querySelector('#commentForm button[type="submit"]');
            submitBtn.disabled = true;

            try {
                const response = await fetch(`/api/announcements/${announcementId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + getAuthToken(),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        content: content,
                        parent_id: parentId
                    })
                });

                if (!response.ok) throw new Error('Failed to post comment');

                // Clear form and reload comments
                document.getElementById('commentContent').value = '';
                loadComments();

                // Show success message
                showAlert('Comment posted successfully!', 'success');
            } catch (error) {
                showAlert('Failed to post comment. Please try again.', 'danger');
            } finally {
                submitBtn.disabled = false;
            }
        }

        // Display comments recursively
        function displayComments(comments) {
            const commentsList = document.getElementById('commentsList');

            if (!comments || comments.length === 0) {
                commentsList.innerHTML = '<p class="text-muted">No comments yet. Be the first to comment!</p>';
                return;
            }

            let html = '';
            comments.forEach(comment => {
                html += renderComment(comment);
            });
            commentsList.innerHTML = html;
        }

        // Render a single comment with nested replies
        function renderComment(comment, depth = 0) {
            const indent = depth * 30;
            const userName = comment.user ? comment.user.name : 'Unknown User';
            const commentDate = moment(comment.created_at).fromNow();
            const commentDateFull = moment(comment.created_at).format('MMM D, YYYY [at] h:mm A');

            // Check if comment is deleted
            const isDeleted = comment.is_deleted;
            const commentText = isDeleted ? '<em class="text-muted">This comment was deleted by the user</em>' : escapeHtml(comment.content);

            let html = `
                <div class="comment-item mb-3" style="margin-left: ${indent}px; border-left: ${depth > 0 ? '2px solid #dee2e6' : 'none'}; padding-left: ${depth > 0 ? '15px' : '0'};">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>${isDeleted ? '[deleted]' : userName}</strong>
                                    <small class="text-muted ms-2" title="${commentDateFull}">${commentDate}</small>
                                </div>
                            </div>
                            <p class="mb-2 mt-1" id="commentText-${comment.id}">${commentText}</p>

                            <!-- Edit Form (hidden by default) -->
                            <div id="editForm-${comment.id}" class="edit-form mt-2" style="display: none;">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="editContent-${comment.id}" value="${escapeHtml(comment.content)}">
                                    <button class="btn btn-sm btn-success" onclick="saveEdit(${comment.id})">Save</button>
                                    <button class="btn btn-sm btn-secondary" onclick="cancelEdit(${comment.id})">Cancel</button>
                                </div>
                            </div>

                            <div class="comment-actions">
                                ${!isDeleted ? `
                                    <button class="btn btn-sm btn-link text-muted p-0 me-2" onclick="showReplyForm(${comment.id})">
                                        <i class='bx bx-reply'></i> Reply
                                    </button>
                                    <button class="btn btn-sm btn-link text-primary p-0 me-2" onclick="showEditForm(${comment.id})">
                                        <i class='bx bx-edit'></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger p-0" onclick="deleteComment(${comment.id})">
                                        <i class='bx bx-trash'></i> Delete
                                    </button>
                                ` : ''}
                            </div>

                            <div id="replyForm-${comment.id}" class="reply-form mt-2" style="display: none;">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="replyContent-${comment.id}" placeholder="Write a reply...">
                                    <button class="btn btn-sm btn-primary" onclick="postReply(${comment.id})">Post</button>
                                    <button class="btn btn-sm btn-secondary" onclick="hideReplyForm(${comment.id})">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Render replies recursively
            if (comment.replies && comment.replies.length > 0) {
                comment.replies.forEach(reply => {
                    html += renderComment(reply, depth + 1);
                });
            }

            return html;
        }

        // Show reply form
        function showReplyForm(commentId) {
            document.getElementById(`replyForm-${commentId}`).style.display = 'block';
        }

        // Hide reply form
        function hideReplyForm(commentId) {
            document.getElementById(`replyForm-${commentId}`).style.display = 'none';
            document.getElementById(`replyContent-${commentId}`).value = '';
        }

        // Post a reply
        async function postReply(parentId) {
            const content = document.getElementById(`replyContent-${parentId}`).value.trim();
            if (!content) return;

            try {
                const response = await fetch(`/api/announcements/${announcementId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + getAuthToken(),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        content: content,
                        parent_id: parentId
                    })
                });

                if (!response.ok) throw new Error('Failed to post reply');

                hideReplyForm(parentId);
                loadComments();
                showAlert('Reply posted successfully!', 'success');
            } catch (error) {
                showAlert('Failed to post reply. Please try again.', 'danger');
            }
        }

        // Show edit form
        function showEditForm(commentId) {
            document.getElementById(`commentText-${commentId}`).style.display = 'none';
            document.getElementById(`editForm-${commentId}`).style.display = 'block';
        }

        // Cancel edit
        function cancelEdit(commentId) {
            document.getElementById(`commentText-${commentId}`).style.display = 'block';
            document.getElementById(`editForm-${commentId}`).style.display = 'none';
        }

        // Save edited comment
        async function saveEdit(commentId) {
            const content = document.getElementById(`editContent-${commentId}`).value.trim();
            if (!content) {
                showAlert('Comment cannot be empty', 'warning');
                return;
            }

            try {
                const response = await fetch(`/api/comments/${commentId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + getAuthToken(),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                });

                if (!response.ok) throw new Error('Failed to update comment');

                loadComments();
                showAlert('Comment updated successfully!', 'success');
            } catch (error) {
                showAlert('Failed to update comment. Please try again.', 'danger');
            }
        }

        // Delete comment
        async function deleteComment(commentId) {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }

            try {
                const response = await fetch(`/api/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + getAuthToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to delete comment');

                const data = await response.json();
                loadComments();
                showAlert(data.message || 'Comment deleted successfully!', 'success');
            } catch (error) {
                showAlert('Failed to delete comment. Please try again.', 'danger');
            }
        }

        // Get auth token (you'll need to implement this based on your auth system)
        function getAuthToken() {
            // For testing, you can return a hardcoded token
            // In production, get this from localStorage, session, or meta tag
            return document.querySelector('meta[name="api-token"]')?.content || '';
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Show alert message
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            });
        </script>
    @endif
@endsection
