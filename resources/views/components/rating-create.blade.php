@props(['articleId', 'conversationid'])
<section>
	<article class="flex">
		<div class="result-content">
			<h5 class="rating-title">Give your ratings</h5>
			<br>
			
				<form wire:submit.prevent="submitRating"  method="POST" id="rating-stars">
					@csrf
					<div class="rating">
						@foreach(range(1, 5) as $i)
						<x-rating-stars :value="$i" />
						@endforeach
					</div>
					<input type="hidden" name="conversationid" value="{{$conversationid}}" />
					<input class="float-right submit-btn" id="feedback-submit" name="feedback-submit" type="submit" value="Submit">
				</form>
			<br>
			<br>
		</div>
	</article>
</section>