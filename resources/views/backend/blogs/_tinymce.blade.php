@push('styles')
<style>
	.tox-tinymce { border-radius: 8px !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" crossorigin="anonymous"></script>
<script>
(function () {
	var uploadUrl = @json(route('admin.blogs.tinymce-upload'));
	var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

	function uploadHandler(blobInfo, progress) {
		return new Promise(function (resolve, reject) {
			var xhr = new XMLHttpRequest();
			xhr.open('POST', uploadUrl);
			xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.onerror = function () { reject('Image upload failed (network).'); };
			xhr.onload = function () {
				if (xhr.status < 200 || xhr.status >= 300) {
					reject('HTTP ' + xhr.status);
					return;
				}
				try {
					var json = JSON.parse(xhr.responseText);
					if (!json || typeof json.location !== 'string') {
						reject('Invalid upload response.');
						return;
					}
					resolve(json.location);
				} catch (e) {
					reject('Invalid JSON from server.');
				}
			};
			var formData = new FormData();
			formData.append('file', blobInfo.blob(), blobInfo.filename());
			xhr.send(formData);
		});
	}

	tinymce.init({
		selector: '#blog_body',
		height: 480,
		menubar: false,
		license_key: 'gpl',
		plugins: 'link lists image code table autoresize autolink',
		toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code removeformat',
		block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Preformatted=pre',
		content_style: 'body { font-family: Montserrat, system-ui, sans-serif; font-size: 15px; line-height: 1.65; }',
		relative_urls: false,
		remove_script_host: false,
		convert_urls: false,
		images_upload_handler: uploadHandler,
		setup: function (editor) {
			editor.on('change keyup', function () {
				editor.save();
			});
		}
	});

	document.getElementById('blog_post_form')?.addEventListener('submit', function () {
		if (typeof tinymce !== 'undefined') {
			tinymce.triggerSave();
		}
	});
})();
</script>
@endpush
