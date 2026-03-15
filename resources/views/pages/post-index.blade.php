<form wire:submit="save">
	<label>
		Title
		<input class="border"
		       type="text"
		       wire:model="title"
		>
		@error('title')
		<span style="color: red;">{{ $message }}</span>
		@enderror
	</label>

	<label>
		Content
		<textarea class="border"
		          wire:model="content"
		          rows="5"
		></textarea>
		@error('content')
		<span style="color: red;">{{ $message }}</span>
		@enderror
	</label>

	<button class="border rounded-sm cursor-pointer" type="submit">Save Post</button>
</form>
