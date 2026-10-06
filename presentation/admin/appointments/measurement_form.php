<?php
$id = $_GET['id'];
?>

<form id="measurementForm">

    <input type="hidden" name="appointment_id" value="<?= $id ?>">

    <div class="mb-2">
        <label>Area Name</label>
        <input type="text" name="area_name"
            class="form-control" required>
    </div>

    <div class="mb-2">
        <label>Width</label>
        <input type="number" step="0.01"
            name="width" class="form-control">
    </div>

    <div class="mb-2">
        <label>Height</label>
        <input type="number" step="0.01"
            name="height" class="form-control">
    </div>

    <div class="mb-2">
        <label>Length</label>
        <input type="number" step="0.01"
            name="length" class="form-control">
    </div>

    <div class="mb-2">
        <label>Notes</label>
        <textarea name="notes"
            class="form-control"></textarea>
    </div>

    <button class="btn btn-dark  w-100">
        Save Measurement
    </button>

</form>