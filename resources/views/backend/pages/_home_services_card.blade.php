<div class="card radius-10 border">
	<div class="card-body">
		<h5 class="mb-3 pb-2 border-bottom">Home — services strip heading</h5>
		<p class="text-muted small mb-3">Service cards still come from <strong>Services</strong> in the admin menu; this only changes the section title and intro.</p>
		<div class="row g-3">
			<div class="col-md-4">
				<label class="form-label">Kicker</label>
				<input type="text" name="home_services_kicker" class="form-control" value="{{ old('home_services_kicker', $page->section('services.kicker')) }}">
			</div>
			<div class="col-md-8">
				<label class="form-label">Title (H2)</label>
				<input type="text" name="home_services_title" class="form-control" value="{{ old('home_services_title', $page->section('services.title')) }}">
			</div>
			<div class="col-12">
				<label class="form-label">Lead line</label>
				<input type="text" name="home_services_lead" class="form-control" value="{{ old('home_services_lead', $page->section('services.lead')) }}">
			</div>
		</div>
	</div>
</div>
