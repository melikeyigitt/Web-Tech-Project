function toggleCommentForm(postId) {
    var commentForm = document.getElementById('comment-form-' + postId);
    if (commentForm.style.display === 'none') {
        commentForm.style.display = 'block';
    } else {
        commentForm.style.display = 'none';
    }
}

function changeMonth() {
    const monthSelector = document.getElementById('month-selector');
    const selectedMonth = monthSelector.value;
    window.location.href = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?month=" + selectedMonth;
}