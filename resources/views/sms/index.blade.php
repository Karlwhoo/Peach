@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row g-4">
        <!-- Email Form Card -->
        <div class="col-lg-8">
            <div class="card card-primary shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title d-flex align-items-center m-0">
                        <a href="{{ asset('room') }}" class="me-3">
                            <i class="fa-solid fa-circle-arrow-left fs-5 text-white" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to List"></i>
                        </a>
                        <i class="fas fa-envelope me-2"></i> Compose Email
                    </h2>
                </div>

                <form id="emailForm" class="needs-validation" novalidate>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-11">
                                <!-- Recipients Field -->
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-user me-2"></i>To
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-at"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control" 
                                            placeholder="recipient@example.com" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address
                                    </div>
                                </div>

                                <!-- Subject Field -->
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-heading me-2"></i>Subject
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                        <input type="text" name="subject" class="form-control" 
                                            placeholder="Enter subject" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a subject
                                    </div>
                                </div>

                                <!-- Message Field -->
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-pen me-2"></i>Message
                                    </label>
                                    <textarea name="message" class="form-control" rows="8" 
                                        placeholder="Enter your message" required></textarea>
                                    <div class="invalid-feedback">
                                        Please enter a message
                                    </div>
                                    <div class="form-text mt-2">
                                        <span id="charCount">0</span> characters
                                    </div>
                                </div>

                                <!-- Attachments Field -->
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-paperclip me-2"></i>Attachments
                                    </label>
                                    <div class="input-group">
                                        <input type="file" name="attachments[]" class="form-control" multiple>
                                    </div>
                                    <div class="form-text mt-2">
                                        Maximum file size: 10MB. Allowed files: PDF, DOC, DOCX, JPG, PNG
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="sendButton">
                                <i class="fas fa-paper-plane me-2"></i>Send Email
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="card-title m-0">
                        <i class="fas fa-eye me-2"></i>Preview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="preview-box border rounded p-3 bg-light">
                        <div class="preview-to mb-2">
                            <small class="text-muted">To:</small>
                            <div id="previewTo" class="text-truncate">-</div>
                        </div>
                        <div class="preview-subject mb-2">
                            <small class="text-muted">Subject:</small>
                            <div id="previewSubject" class="text-truncate">-</div>
                        </div>
                        <div class="preview-message">
                            <small class="text-muted">Message:</small>
                            <div id="previewMessage" class="text-break">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.card {
    border: none;
    margin-bottom: 1.5rem;
}

.preview-box {
    min-height: 300px;
}

.input-group-text {
    border: none;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

#sendButton:disabled {
    cursor: not-allowed;
}

.invalid-feedback {
    font-size: 0.875em;
}

.container-fluid {
    max-width: 1800px;
    margin: 0 auto;
}

.card-body {
    padding: 1.5rem;
}

.swal2-popup {
    font-size: 0.9rem;
}

.swal2-title {
    font-size: 1.5rem;
}

.swal2-confirm {
    padding: 0.5rem 1.5rem !important;
}

.swal2-actions {
    margin-top: 1.5rem !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

    // Real-time preview updates
    const emailInput = document.querySelector('input[name="email"]');
    const subjectInput = document.querySelector('input[name="subject"]');
    const messageInput = document.querySelector('textarea[name="message"]');
    const charCount = document.getElementById('charCount');

    function updatePreviews() {
        document.getElementById('previewTo').textContent = emailInput.value || '-';
        document.getElementById('previewSubject').textContent = subjectInput.value || '-';
        document.getElementById('previewMessage').textContent = messageInput.value || '-';
        charCount.textContent = messageInput.value.length;
    }

    emailInput.addEventListener('input', updatePreviews);
    subjectInput.addEventListener('input', updatePreviews);
    messageInput.addEventListener('input', updatePreviews);

    // Replace the openGmail function with new form submission
    const form = document.getElementById('emailForm');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const sendButton = document.getElementById('sendButton');
        sendButton.disabled = true;
        sendButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

        // Show loading state
        Swal.fire({
            title: 'Sending Email',
            html: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('email', document.querySelector('input[name="email"]').value);
        formData.append('subject', document.querySelector('input[name="subject"]').value);
        formData.append('message', document.querySelector('textarea[name="message"]').value);
        
        // Add files to FormData
        const fileInput = document.querySelector('input[name="attachments[]"]');
        for (let file of fileInput.files) {
            formData.append('attachments[]', file);
        }

        try {
            const response = await fetch('/send-email', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    // Remove Content-Type header to let browser set it automatically with boundary
                },
                body: formData
            });

            if (!response.ok) {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const result = await response.json();
                    throw new Error(result.message || 'Failed to send email');
                } else {
                    const text = await response.text();
                    throw new Error('Server error: ' + response.status);
                }
            }

            const result = await response.json();
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Email sent successfully',
                confirmButtonColor: '#0d6efd'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.reset();
                    updatePreviews();
                }
            });
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error sending email: ' + error.message,
                confirmButtonColor: '#dc3545'
            });
        } finally {
            sendButton.disabled = false;
            sendButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Email';
        }
    });

    // Add file size validation
    const attachmentInput = document.querySelector('input[name="attachments[]"]');
    attachmentInput.addEventListener('change', function(e) {
        const files = e.target.files;
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
        
        for (let file of files) {
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Too Large',
                    text: `${file.name} is larger than 10MB`,
                    confirmButtonColor: '#ffc107'
                });
                e.target.value = ''; // Clear the input
                return;
            }
            
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid File Type',
                    text: `${file.name} is not an allowed file type`,
                    confirmButtonColor: '#ffc107'
                });
                e.target.value = ''; // Clear the input
                return;
            }
        }
    });
});
</script>
@endsection
