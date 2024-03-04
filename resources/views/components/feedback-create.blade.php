@props(['articleId', 'conversationid','sections'])

<section>
	<article class="flex">
		<div class="result-content">
			<h5 class="rating-title">Write your own review</h5>
			<br>
			<form wire:submit.prevent="submitFeedback"  method="POST" id="rating-star">
				@csrf
				<!-- <div class="rating">
					@foreach(range(1, 5) as $i)
					<x-rating-stars :value="$i" />
					@endforeach
				</div> -->

				<div class="rating-text">
					<p class="font-bold">Please provide a feedback on the article
						provided.</p>
					<select class="form-control" name="section" required  wire:model="sectionmain">
						<option value=""> -- Select --</option>
						<?php foreach ($sections->values as $key => $value) { ?>
								<option value="{{$value->id}}">{{$value->name}}</option>
						<?php } ?>
					</select><br/>
					<select class="form-control" name="sectionid" required  wire:model="sectionid">
						<option value="">-- Select --</option>
						<?php foreach ($sections->values as $key => $value) {
								foreach($value->subSections as $rows){ ?>
									<option value="{{$rows->id}}">{{$rows->name}}</option>
							<?php	}
						 ?>
								
						<?php } ?>
					</select>
					<br/>
					<input type="text" placeholder="Title" class="form-control" name="title" required  wire:model="title" />	<br/>
					<textarea wire:model="review"  required name="review" placeholder="Feedback" class="focus:outline-none focus:ring"></textarea>
				</div>
				<input type="hidden" name="conversationid" value="{{$conversationid}}" />
				<input class="float-right submit-btn" id="feedback-submit" name="feedback-submit" type="submit" value="Submit">
			</form>
			<br>
			<br>
		</div>
	</article>
</section>

