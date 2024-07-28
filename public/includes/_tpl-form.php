<form id="forecast-form" action="/" class="col-md-4">

    <div class="alert alert-danger d-none"></div>

    <div class="form-group">
        <label for="city">City name <span class="req">*</span></label>
        <input type="text" class="form-control" id="city" name="city" value="<?= isset( $_GET['city'] ) ? $_GET['city'] : null ?>" required="required">
    </div>

    <div class="form-group">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" id="date" name="date" value="<?= isset( $_GET['date'] ) ? $_GET['date'] : null ?>">
    </div>

    <button type="submit" class="btn btn-primary mt-4">Submit</button>

</form>