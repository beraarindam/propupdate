<div class="row g-4">
	<div class="col-lg-8">
		<div class="card radius-10 border">
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">Question</label>
					<input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required maxlength="500">
				</div>
				<div class="mb-0">
					<label class="form-label">Answer</label>
					<textarea name="answer" class="form-control" rows="8" required maxlength="20000">{{ old('answer', $faq->answer) }}</textarea>
					<p class="text-muted small mt-2 mb-0">Plain text; line breaks are preserved when you display FAQs publicly.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0">
					<p class="text-muted small mt-1 mb-0">Lower numbers appear first.</p>
				</div>
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="faq_pub" @checked(old('is_published', $faq->is_published ?? true))>
					<label class="form-check-label" for="faq_pub">Published</label>
				</div>
			</div>
		</div>
	</div>
</div>
