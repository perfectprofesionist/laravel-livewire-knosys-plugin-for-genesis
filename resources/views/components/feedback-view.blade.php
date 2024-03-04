@props(['feedback'])
@if($feedback["count"] > 0)
<section>
	<h5 class="rating-title" style="margin-left: 40px;">User Reviews</h5>
	<div class="review-box labels">
		<ul id="feedback-list" style="padding-left: 0rem;">
			@foreach($feedback["review"] as $review)
			 @if(!empty($review['rating_text']))
			<li style="padding-left: 3rem;">
				<div class="tab-left">
					<!-- @if($review['rating_star'] !=0)
					<x-stars :value="$review['rating_star']" :color="'#FDCC0D'" />
					<x-stars :value="5-$review['rating_star']" :color="'#d4d4d4'" />
					@endif -->
					<p class="text-xs">
						Posted
						<time>{{ $review['created_at'] }}</time>
					</p>
					<h6>{{$review['name']}}</h6>
					<span>{{$review['rating_text']}}</span>
				</div>
			</li> 
				@endif
			@endforeach
			<li></li>
		</ul>
	</div>
</section>
@endif