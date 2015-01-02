/**
 * @todo: create standard response with same methods as on backend
 * @todo: implement mechanism to shring js and remove comments from js
 * @todo: do some OO in posts.js. everything is old style flat code.
 * @param response
 */
function xAddPostSent(response, form) {
    if (response && response.result == 'ERROR') {
        displayError(response.errorMessage);
    }

    if (response && response.result == 'OK') {
        cleanError();
        loadPostsList();
        displaySuccess('Success! Thanks for your contribution')
        if (form.reset) {
            form.reset();
        }
    }

}

function xCommentSent(response, postId) {
    if (response && response.result == 'ERROR') {
        displayError(response.errorMessage);
    }

    if (response && response.result == 'OK') {
        cleanError();
        loadPostsList();
        displaySuccess('Success! Thanks for your contribution')
    }

}


function cleanError() {
    displayError('');
}

function displaySuccess(text) {
    if (text) {
        cleanError();
    }
    document.getElementById('success').innerHTML = text;
}

function cleanSuccess() {
    displaySuccess('');
}

function displayError(text) {
    if (text) {
        cleanSuccess();
    }
    document.getElementById('errors').innerHTML = text;
}

function loadPostsList(response, param) {
    var xPostsList = ajaxMaster.getRequester('get', '?action=posts/list',   displayPostList, param);
    xPostsList.send();
}
function displayPostList(response) {
    document.getElementById("posts").innerHTML = response;
    var addCommentForms = document.querySelectorAll('div[id^="addComment_"]');
    for (var i=0; i< addCommentForms.length; i++) {
        // handle ajax form submission
        addCommentForms[i].onsubmit = function() {
            var postId = this.getAttribute('id').match(/addComment_([0-9]+)/)[1];
            var xAddComment = ajaxMaster.getRequester('post', '?action=comments', xCommentSent);
            var name = this.querySelector('input[class="name"]').value;
            var email = this.querySelector('input[class="email"]').value;
            var message = this.querySelector('textArea[class="commentMessage"]').value;
            xAddComment.send("name=" + name + "&email=" + email + "&message=" + message + "&id_post=" + postId);
            return false;
        }

    }
    displayComments();
}

function displayComments() {
    var commentsCount = document.querySelectorAll('span[id^="ccount_"]');
    for (var i=0; i<commentsCount.length; i++) {
        if (parseInt(commentsCount[i].innerHTML) > 0) {
            var postId = commentsCount[i].getAttribute('id').match(/ccount_([0-9]+)/)[1];
            var xCommentsList = ajaxMaster.getRequester(
                'get', '?action=comments&id_post=' + postId,
                requestCommentsForPost, document.getElementById('comments_' + postId));
            xCommentsList.send();
        }
    }

    formatOutput();
}

function formatOutput() {
    //links
    var messageFields = document.querySelectorAll('div[class="message"]');
    for (var i=0; i<messageFields.length; i++) {
        messageFields[i].innerHTML = messageFields[i].innerHTML.replace(
            /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi,
            function(match) {
                if (match.indexOf('http') == -1) {
                    match = 'http://' + match;
                }
                return '<a href="' + match + '" target="blank">' + match + '</a>';
            }
        );
    }

}

function requestCommentsForPost(response, divTarget) {
    divTarget.innerHTML = response;
}

window.onload = function() {
    /**
     * add post submission handling
     */
    var myForm = document.getElementById("addPostForm");
    myForm.onsubmit = function() {
        var xAddPost = ajaxMaster.getRequester('post', '', xAddPostSent, myForm);
        var name = document.getElementById("name").value;
        var email = document.getElementById("email").value;
        var message = document.getElementById("postmessage").value;
        xAddPost.send("name=" + name + "&email=" + email + "&message=" + message);
        return false;
    }

    loadPostsList();
}
