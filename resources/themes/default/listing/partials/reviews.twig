{% if comments | length > 0 %}
    <a href="{{ route('reviews.index', [listing, listing.slug]) }}" class="d-sm-none small text-muted"><i class="fa fa-angle-right" aria-hidden="true"></i> {{ __("Read reviews") }}</a>
{% endif %}
<div class="card d-none d-sm-block">
    <div class="card-header">
        {{ __("Customer reviews") }}
    </div>
    <div class="card-body">
        {% if comments | length > 0 %}

            <h6 class="mt-0">{{ __("Average customer rating") }}</h6>

            {{ include('components.star_rating', {rating: listing.averageRate()}) }}
            <span class="ml-3">({{ __(":count reviews", {'count':comment_count}) }})</span><br/><br/>
            <a href="{{ route('reviews.create', [listing, listing.slug]) }}" class="btn btn-outline-primary"><i class="mdi mdi-plus"></i> {{ __("Add your own review") }}</a>
            <br/><br/><strong class="text-muted">{{ __("Showing most recent reviews") }}</strong>  <a
                href="{{ route('reviews.index', [listing, listing.slug]) }}">({{ __("see all reviews") }})</a>
            <hr class="mt-0 pt-0"/>

        {% endif %}
        {% if comments | length == 0 %}
            <strong>{{ __('There are no customer reviews yet.') }}</strong><br/><br/>
            <a href="{{ route('reviews.create', [listing, listing.slug]) }}" class="btn btn-primary"><i class="mdi mdi-plus"></i> {{ __("Be the first to write a review") }}</a>
        {% else %}

            {% for comment in comments %}
                <div class="row mb-5">

                    <div class="col-8">

                        <h6 class="pt-0">{{ comment.commenter.display_name }} </h6>
                        {{ include('components.star_rating', {rating: comment.rating}) }}
                    </div>
                    <div class="col-12 mt-2">
                        <p class="mb-0">{{ comment.comment }}</p>
                        <span class="small text-muted">{{ __('Published') }} {{ comment.created_at.toFormattedDateString() }}</span>
                    </div>
                </div>

            {% endfor %}

        {% endif %}
    </div>
</div>
