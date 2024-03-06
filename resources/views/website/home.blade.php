@extends('layouts.layout')

@section('content')
<div class="px-3  d-flex row mx-0 h-100 w-100 justify-content-center">
    <div aria-live="polite" aria-atomic="true" class="bg-dark position-relative bd-example-toasts">
        <div class="toast-container position-absolute p-3 top-0 end-0 rounded-3 translate-middle-y" id="toastPlacement">
            <div class="toast rounded-3 border-0">
                <div class="toast-header bg-danger border-0 rounded-3">
                    <strong class="me-auto text-white">Something went wrong</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto card p-5 __form_container col-md-6" id='__main_content'>
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
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function getSuggestions(e) {
        e.preventDefault();
        $('#submitBtn').html(`<span>Generating... 
            <div class="spinner-border spinner-border-sm" role="status">
              <span class="visually-hidden">Generating...</span>
            </div>
        </span>`);
        if ($('#moreBtn').length) {
            $(this).html(`<span>More
                </span>`);
        }
        var formData = $('#sugForm').serialize();
        $.ajax({
            type: 'POST',
            url: '/get_suggetions',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#sugForm').hide();
                $('#resultArea').show();
                $('#resultArea').html(response?.data?.map((item) => {
                    var ratingHtml = '';
                    for (var i = 1; i <= item?.rating; i++) ratingHtml += '<i class="fa fa-star rating-color"></i>';
                    return `<div class="card w-100 p-3 my-3 __result_card">
                        <h3>${item?.title}</h3>
                        <p>${item?.description}</p>
                        <div class="my-3 d-flex justify-content-between align-items-center">
                        <h6 class="review-stat">Rating</h6>
                            <div class="small-ratings">
                            ${ratingHtml}
                            </div>           
                     </div>
                        </div>`
                }));
                $('#resultArea').append('<div class="w-100 d-flex justify-content-center"><button class="btn btn-secondary m-2" id="resetBtn">Reset</button><button class="btn btn-secondary m-2" id="moreBtn">More</button></div>');
                $('#resetBtn').click(function() {
                    $('#sugForm').show();
                    $('#resultArea').hide();
                    if ($('#submitBtn').length) {
                        $('#submitBtn').html(`<span>Generate</span>`);
                    }
                })
                $('#moreBtn').click(function() {
                    $(this).html(`<span>Generating... 
                    <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Generating...</span>
                    </div>
                </span>`);
                    getSuggestions(e);
                })
            },
            error: function(response) {
                $('.toast').toast('show');
            }
        });
    };


    $(document).ready(function() {
        $('#resultArea').hide();
        $('#sugForm').submit(function(e) {
            getSuggestions(e);
        });
    });
</script>

@endsection