@extends('layouts.layout')

@section('content')
<div class="px-3  d-flex row mx-0 h-100 w-100 justify-content-center">
    <div class="container mx-auto card p-5 __form_container col-md-6">
        <h1 class="text-center">Movie/Book Recommender</h1>
        <p class="fs-5 text-center">Discover Your Next Favorite Movie or Book: Get Personalized Recommendations Now</p>
        <form class="__form" id='sugForm'>
            <div class="mb-3">
                <label for="favorite" class="form-label">Favorite Movie or Book</label>
                <input type="text" class="form-control" id="favorite" name="favorite" placeholder="Enter your favorite movie or book" required>
            </div>
            <div class="mb-3">
                <label for="recommenderType" class="form-label">Select a Recommender</label>
                <select class="form-select" id="recommenderType" name="recommenderType" required>
                    <option selected disabled>Select a recommender</option>
                    <option value="movie">Movie Recommender</option>
                    <option value="book">Book Recommender</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="numRecommendations" class="form-label">Number of Recommendations</label>
                <input type="number" class="form-control" id="numRecommendations" name="numRecommendations" min="1" max="10" value="2" required>
            </div>
            <div class="text-center"><button type="submit" class="btn btn-secondary" id='submitBtn'>Generate</button></div>
        </form>
        <div class="" id="resultArea">

        </div>
        <div class="py-3">
            <div class="progress" id="pro_bar">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function getSuggestions(e) {
        e.preventDefault();
        $('#pro_bar').show();
        var formData = $('#sugForm').serialize();
        $.ajax({
            type: 'POST',
            url: '/get_suggetions',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                $('#pro_bar').hide();
                $('#sugForm').hide();
                $('#resultArea').show();
                $('#resultArea').html(response?.data?.map((item) => {
                    return `<div class="card w-100 p-3 my-2 __result_card">
                        <h3>${item?.title}</h3>
                        <p>${item?.description}</p>
                        </div>`
                }));
                $('#resultArea').append('<div class="w-100 d-flex justify-content-center"><button class="btn btn-secondary m-2" id="resetBtn">Reset</button><button class="btn btn-secondary m-2" id="moreBtn">More</button></div>');
                $('#resetBtn').click(function() {
                    $('#sugForm').show();
                    $('#resultArea').hide();
                })
                $('#moreBtn').click(function() {
                    getSuggestions(e);
                })
            },
            error: function(response) {
                console.log(response);
                $('#pro_bar').hide();
            }
        });
    };


    $(document).ready(function() {
        $('#pro_bar').hide();
        $('#resultArea').hide();
        $('#submitBtn').click(function(e) {
            getSuggestions(e);
        });
    });
</script>

@endsection