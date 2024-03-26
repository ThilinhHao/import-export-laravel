  // Set timeout flash message
  if (document.querySelector("#flash-message")) {
    setTimeout(() => {
        document.querySelector("#flash-message").remove();
    }, 5000);
}

function deleteModal(newsId, newsTitle, route){
    $("#content").html("");

    var str =
        `<div class="modal fade" id="staticBackdrop` + newsId + `" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Delete news</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Are you sure delete <span class="text-error-notify">` + newsTitle + `</span> ?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                        </button>
                        <form action="` + route + `" method="GET">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>`
    $("#modal").append(str);
    $("#staticBackdrop" + newsId).modal("show");
}

// CKEditor description
ClassicEditor
.create(document.querySelector('#description'), {
    ckfinder: {
        uploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
    },
    toolbar: ['ckfinder', 'imageUpload', '|', 'heading', '|', 'bold', 'italic', '|', 'undo', 'redo']
})
.catch(function(error) {
    console.error(error);
});

// Spinner prevent spam click
function showLoadingSpinner(event) {
    event.preventDefault();

    if ($(this).valid()) {
        const btnSave = this.querySelector('#btn-save');
        const btnLoading = this.querySelector('#btn-loading');

        if (btnSave && btnLoading) {
            btnSave.style.display = 'none';
            btnLoading.style.display = 'inline-block';
            btnSave.setAttribute('type', 'button');

            this.submit();
        }
    }
}

// preview Image
function previewImage(event) {
    const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    const file = event.target.files[0];
    const messageAvatar = document.getElementById('message_avatar');
    const output = document.getElementById('preview-image');
    const avatarValue = document.getElementById('avatar-value');

    if (!allowedExtensions.exec(file.name)) {
        messageAvatar.textContent = 'The avatar must be an image.';
        event.target.value = '';
        return false;
    }

    const reader = new FileReader();

    reader.onload = function () {
        output.src = reader.result;
        output.style.display = "block";
        avatarValue.value = reader.result;
    };

    reader.readAsDataURL(file);
    messageAvatar.textContent = '';
}

// Image
const imageInput = document.getElementById('image');
if (imageInput) {
 imageInput.addEventListener('change', previewImage);
}
