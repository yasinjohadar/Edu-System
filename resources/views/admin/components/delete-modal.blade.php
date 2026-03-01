<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-body text-center p-5">
                <!-- Icon -->
                <div class="mb-4">
                    <div style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h4 class="mb-3" style="color: #2c3e50; font-weight: 600; font-family: 'Cairo', sans-serif;">
                    تأكيد الحذف
                </h4>
                
                <!-- Message -->
                <p class="mb-4" style="color: #7f8c8d; font-size: 15px; line-height: 1.6; font-family: 'Cairo', sans-serif;">
                    <span id="deleteModalMessage">هل أنت متأكد من رغبتك في حذف هذا العنصر؟</span>
                    <br>
                    <span style="color: #e74c3c; font-weight: 600;">لا يمكن التراجع عن هذه العملية!</span>
                </p>
                
                <!-- Actions -->
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="min-width: 120px; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-family: 'Cairo', sans-serif; border: 2px solid #e0e0e0; transition: all 0.3s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        إلغاء
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="min-width: 120px; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-family: 'Cairo', sans-serif; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); border: none; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4); transition: all 0.3s;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; vertical-align: middle;">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete button clicks
    document.querySelectorAll('[data-delete-url]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const deleteUrl = this.getAttribute('data-delete-url');
            const deleteMessage = this.getAttribute('data-delete-message') || 'هل أنت متأكد من رغبتك في حذف هذا العنصر؟';
            
            // Set form action
            const deleteForm = document.getElementById('deleteForm');
            if (deleteForm) {
                deleteForm.action = deleteUrl;
            }
            
            // Set message
            const messageElement = document.getElementById('deleteModalMessage');
            if (messageElement) {
                messageElement.innerHTML = deleteMessage;
            }
            
            // Show modal using Bootstrap 5
            const modalElement = document.getElementById('deleteModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    });
});
</script>
@endpush
