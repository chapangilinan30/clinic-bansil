<div id="completeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-[40px] p-10 max-w-md w-full mx-4 shadow-2xl border-t-8 border-[#7BA7B4]">
        <div class="text-center">
            <h3 class="text-2xl font-bold text-[#2D5A71] mb-2">Finish Session?</h3>
            <p class="text-gray-500 mb-8">Are you sure you want to mark <span id="modalPatientName" class="font-bold text-blue-600"></span>'s appointment as completed?</p>
            
            <form id="completeForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="flex gap-4">
                    <button type="button" onclick="closeCompleteModal()" 
                        class="flex-1 px-6 py-4 border-2 border-gray-100 text-gray-400 rounded-2xl font-bold hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="flex-1 px-6 py-4 bg-[#7BA7B4] text-white rounded-2xl font-bold shadow-lg hover:bg-[#6a96a3]">
                        Yes, Complete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCompleteModal(id, name) {
    const modal = document.getElementById('completeModal');
    const form = document.getElementById('completeForm');
    const nameSpan = document.getElementById('modalPatientName');

    // Use the correct route URL
    form.action = `/clerk/complete/${id}`; 
    nameSpan.innerText = name;
    
    modal.classList.remove('hidden');
}

    function closeCompleteModal() {
        document.getElementById('completeModal').classList.add('hidden');
    }
</script>