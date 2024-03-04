@props(['idForm'])
<div class="settings-display-btns1">
<a id="{{$idForm}}-edit" class= "float-right submit-btn p-1"  data-role="button" style="display:block;" onclick="editButton('{{$idForm}}') ">Edit</a>
<input id="{{$idForm}}-submit"   class= "float-right submit-btn " type="submit" value="Submit" style="display:none;" wire:click="$emit('refreshComponent')" />	
<input type="reset" id="{{$idForm}}-cancel" value="Cancel" class= "float-right submit-btn p-1 " style="display:none;" onclick="cancelButton('{{$idForm}}')" />
</div>