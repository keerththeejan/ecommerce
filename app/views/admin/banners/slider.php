<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Hero Slider Settings</h2>
            <p>Configure your homepage banner slider settings here.</p>
            
            <div class="card">
                <div class="card-body">
                    <h5>Slider Configuration</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Auto Slide</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="autoSlide" checked>
                                <label class="form-check-label" for="autoSlide">
                                    Enable automatic sliding
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slide Interval (seconds)</label>
                            <input type="number" class="form-control" value="5" min="1" max="60">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Transition Effect</label>
                            <select class="form-control">
                                <option>Fade</option>
                                <option>Slide</option>
                                <option>Zoom</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
