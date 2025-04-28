function openEditModal(id, title, description) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('editTaskId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDescription').value = description;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}