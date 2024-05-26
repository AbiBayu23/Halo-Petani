document.addEventListener("DOMContentLoaded", function() {
    var likeBtns = document.querySelectorAll(".like-btn");

    likeBtns.forEach(function(btn) {
        btn.addEventListener("click", function() {
            var likeCount = this.nextElementSibling;
            var isLiked = this.getAttribute("data-liked");

            if (isLiked === "false") {
                likeCount.textContent = parseInt(likeCount.textContent) + 1;
                this.setAttribute("data-liked", "true");
                this.textContent = "Unlike";
            } else {
                likeCount.textContent = parseInt(likeCount.textContent) - 1;
                this.setAttribute("data-liked", "false");
                this.textContent = "Like";
            }
        });
    });
});
