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
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="sendButton" onclick="openGmail(event)">
                                <i class="fas fa-paper-plane me-2"></i>Open in Gmail
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
});

function openGmail(event) {
    event.preventDefault();
    
    const form = document.getElementById('emailForm');
    const sendButton = document.getElementById('sendButton');

    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    // Disable button and show loading state
    sendButton.disabled = true;
    sendButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Opening Gmail...';

    const email = document.querySelector('input[name="email"]').value;
    const subject = document.querySelector('input[name="subject"]').value;
    const message = document.querySelector('textarea[name="message"]').value;

    const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(message)}`;
    window.open(gmailUrl, '_blank');

    // Reset button state
    setTimeout(() => {
        sendButton.disabled = false;
        sendButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Open in Gmail';
    }, 1000);
}
</script>
@endsection
