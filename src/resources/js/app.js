require("./bootstrap");

// CSRFトークンを取得
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// いいね機能
function likePost(postId) {
    fetch(`/posts/${postId}/like`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const likesCountElement = document.getElementById(
                    `likes-count-${postId}`
                );
                if (likesCountElement) {
                    likesCountElement.textContent = (
                        parseInt(likesCountElement.textContent) + 1
                    ).toString();
                } else {
                    alert(data.message); // すでにいいねしている場合のメッセージを表示
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert(`Error: ${error.message}`); // エラーメッセージをアラートで表示
        });
}

function likeComment(commentId) {
    const postId = document.querySelector(`.post[data-id="${commentId}"]`)
        .dataset.id; // 親投稿のIDを取得
    fetch(`/comments/${commentId}/like`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const likeCountElement = document.getElementById(
                    `like-count-${commentId}`
                );
                if (likeCountElement) {
                    likeCountElement.textContent = data.likes_count.toString(); // 新しい「いいね」の数に更新
                }
            } else {
                alert(data.message); // すでにいいねしている場合のメッセージを表示
            }
        })
        .catch((error) => console.error("Error:", error));
}

// なるほど機能
function agreePost(postId) {
    fetch(`/posts/${postId}/agree`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const agreeCountElement = document.getElementById(
                    `agree-count-${postId}`
                );
                if (agreeCountElement) {
                    agreeCountElement.textContent = (
                        parseInt(agreeCountElement.textContent) + 1
                    ).toString();
                } else {
                    alert(data.message); // すでに「なるほど」している場合のメッセージを表示
                }
            }
        });
}

function agreeComment(commentId) {
    const postId = document.querySelector(`.post[data-id="${commentId}"]`)
        .dataset.id; // 親投稿のIDを取得
    fetch(`/comments/${commentId}/agree`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({}),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const agreeCountElement = document.getElementById(
                    `agree-count-${commentId}`
                );
                if (agreeCountElement) {
                    agreeCountElement.textContent = data.agree_count.toString(); // 新しい「なるほど」の数に更新
                }
            } else {
                alert(data.message); // すでに「なるほど」している場合のメッセージを表示
            }
        })
        .catch((error) => console.error("Error:", error));
}

// 削除機能
function deletePost(postId) {
    if (confirm("この投稿を削除しますか？")) {
        fetch(`/posts/${postId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("削除に失敗しました。");
                }
                console.log("response.json deletePost");
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    // 投稿をDOMから削除
                    const postElement = document.querySelector(
                        `.post[data-id="${postId}"]`
                    );
                    if (postElement) {
                        postElement.remove();
                    }
                } else {
                    alert(data.message || "削除に失敗しました。");
                }
            })
            .catch((error) => {
                console.error("削除処理中にエラーが発生しました:", error);
                alert("通信エラーが発生しました。");
            });
    }
}

// 返信機能
function toggleReplies(commentId, button) {
    const repliesWrapper = document.getElementById(`replies-${commentId}`);
    if (repliesWrapper) {
        if (
            repliesWrapper.style.display === "none" ||
            repliesWrapper.style.display === ""
        ) {
            repliesWrapper.style.display = "block"; // 返信を表示
            button.textContent = "コメントへの返信を隠す"; // ボタンのテキストを変更
        } else {
            repliesWrapper.style.display = "none"; // 返信を非表示
            button.textContent = `コメントへの返信（${repliesWrapper.children.length}）を表示`; // ボタンのテキストを元に戻す
        }
    }
}

// 特定の投稿に関連するコメントを表示または非表示にする
function toggleComments(postId) {
    const commentsWrapper = document.getElementById(`all-comments-${postId}`);
    if (commentsWrapper.style.display === "none") {
        commentsWrapper.style.display = "block"; // コメントを表示
    } else {
        commentsWrapper.style.display = "none"; // コメントを非表示
    }
}

document.addEventListener("DOMContentLoaded", function () {
    //必要な初期化処理をここに記述
});
