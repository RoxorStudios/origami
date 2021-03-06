@extends('origami::layouts.master')

@section('content')

<div class="section">
	<div class="box">
		<div class="padding">
			<div class="actions">
				<a href="{{ origami_url('/modules/'.$module->uid.'/fields') }}" class="button button-action button-gray">@lang('origami::global.cancel')</a>
			</div>
			<h1 class="boxtitle">{{ $field->id ? trans('origami::module.fields.edit', ['name' => $module->name]) : trans('origami::module.fields.add', ['name' => $module->name]) }}</h1>
			<form method="POST" action="{{ !$field->uid ? origami_url('/modules/'.$module->uid.'/fields/create') : origami_url('/modules/'.$module->uid.'/fields/'.$field->uid) }}">
				{{ csrf_field() }}
				@include('origami::partials.messages')
				@include('origami::partials.errors')

				<div class="grid">
					<div class="col-m-12">
						@if(!$field->id)
						<div class="form-group">
							@include('origami::fields.partials.type', ['type'=>'text'])
							@include('origami::fields.partials.type', ['type'=>'textarea'])
							@include('origami::fields.partials.type', ['type'=>'checkbox'])
							@include('origami::fields.partials.type', ['type'=>'select'])
							@include('origami::fields.partials.type', ['type'=>'image'])
							{{-- @include('origami::fields.partials.type', ['type'=>'date']) --}}
							@include('origami::fields.partials.type', ['type'=>'module'])
						</div>
						@else
						<div class="m-b-3">@include('origami::fields.partials.type', ['type'=>$field->type])</div>
						<input type="hidden" name="type" value="{{ $field->type }}">
						@endif
					</div>
					<div class="col-m-12">
						<div class="form-group">
							<label>@lang('origami::global.name')</label>
							<input type="text" name="name" v-model="field.name" class="form-input" autofocus>
						</div>
					</div>
					<div class="col-m-12">
						<div class="form-group" v-if="field.type=='text' || field.type=='textarea' || field.type=='module'">
							<label>@lang('origami::global.description')<small>@lang('origami::field.info.description')</small></label>
							<input type="text" name="description" v-model="field.description" class="form-input">
						</div>
					</div>
					<div class="col-m-12">
						<div class="checboxes">
							@if($module->list)
							<div class="switch-checkbox" v-if="field.type=='text'">
								<label>
									<input type="checkbox" name="default" v-model="field.default" v-bind:value="1">
									<div class="title">@lang('origami::field.info.default')</div>
									<div class="check">
										<div class="handle"></div>
									</div>
								</label>
							</div>
							@endif

							<div class="switch-checkbox" v-if="field.type=='checkbox'">
								<label>
									<input type="checkbox" name="options[checkbox][checked]" v-model="field.options.checkbox.checked" v-bind:value="1">
									<div class="title">@lang('origami::field.info.default_checked')</div>
									<div class="check">
										<div class="handle"></div>
									</div>
								</label>
							</div>

							<div class="switch-checkbox" v-if="field.type=='textarea'">
								<label>
									<input type="checkbox" name="options[textarea][markdown]" v-model="field.options.textarea.markdown" v-bind:value="1">
									<div class="title">@lang('origami::field.info.markdown')</div>
									<div class="check">
										<div class="handle"></div>
									</div>
								</label>
							</div>

							<div v-if="field.type=='image'">
								<div class="switch-checkbox" v-if="field.type=='image'">
									<label>
										<input type="checkbox" name="options[image][multiple]" v-model="field.options.image.multiple" v-bind:value="1">
										<div class="title">@lang('origami::field.info.image_multiple')</div>
										<div class="check">
											<div class="handle"></div>
										</div>
									</label>
								</div>

								<div class="switch-checkbox" v-if="field.type=='image'">
									<label>
										<input type="checkbox" name="options[image][resize][active]" v-model="field.options.image.resize.active" v-bind:value="1">
										<div class="title">@lang('origami::field.info.image_resize')</div>
										<div class="check">
											<div class="handle"></div>
										</div>
									</label>
								</div>

								<div class="group" v-if="field.options.image.resize.active">
									<h6 class="m-b-5">@lang('origami::field.info.image_resize_options')</h6>
									<div class="grid">
										<div class="col-m-6">
											<div class="form-group">
												<label>@lang('origami::field.input.width')</label>
												<input type="text" name="options[image][resize][width]" v-model="field.options.image.resize.width" class="form-input">
											</div>
										</div>
										<div class="col-m-6">
											<div class="form-group">
												<label>@lang('origami::field.input.height')</label>
												<input type="text" name="options[image][resize][height]" v-model="field.options.image.resize.height" class="form-input">
											</div>
										</div>
										<div class="col-m-12">
											<div class="switch-checkbox" v-if="field.type=='image'">
												<label>
													<input type="checkbox" name="options[image][resize][crop]" v-model="field.options.image.resize.crop"  v-bind:value="1">
													<div class="title">@lang('origami::field.input.crop')</div>
													<div class="check">
														<div class="handle"></div>
													</div>
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="switch-checkbox" v-if="field.type=='image'">
									<label>
										<input type="checkbox" name="options[image][thumbnail][active]" v-model="field.options.image.thumbnail.active" v-bind:value="1">
										<div class="title">@lang('origami::field.info.image_create_thumbnails')</div>
										<div class="check">
											<div class="handle"></div>
										</div>
									</label>
								</div>

								<div class="group" v-if="field.options.image.thumbnail.active">
									<h6 class="m-b-5">@lang('origami::field.info.image_thumbnail_options')</h6>
									<div class="grid">
										<div class="col-m-6">
											<div class="form-group">
												<label>@lang('origami::field.input.width')</label>
												<input type="text" name="options[image][thumbnail][width]" v-model="field.options.image.thumbnail.width" class="form-input">
											</div>
										</div>
										<div class="col-m-6">
											<div class="form-group">
												<label>@lang('origami::field.input.height')</label>
												<input type="text" name="options[image][thumbnail][height]" v-model="field.options.image.thumbnail.height" class="form-input">
											</div>
										</div>
										<div class="col-m-12">
											<div class="switch-checkbox" v-if="field.type=='image'">
												<label>
													<input type="checkbox" name="options[image][thumbnail][crop]" v-model="field.options.image.thumbnail.crop"  v-bind:value="1">
													<div class="title">@lang('origami::field.input.crop')</div>
													<div class="check">
														<div class="handle"></div>
													</div>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div v-if="field.type=='select'">

								<div class="form-group">
									<label>@lang('origami::field.options.title')</label>
									<div v-if="!field.options.select.options.length">
										<div class="message message-light m-b-2">@lang('origami::field.options.info.empty')</div>
									</div>
									<ul class="options select-options">
										<li class="option" v-for="(option,index) in field.options.select.options">
											<div class="grid">
												<div class="col-5">
													<input type="text" class="form-input" name="options[select][options][name][]" v-model="option.name" placeholder="name">
												</div>
												<div class="col-5">
													<input type="text" class="form-input" name="options[select][options][value][]" v-model="option.value" placeholder="value">
												</div>
												<div class="col-2">
													<ul class="option-actions">
														<li v-on:click="field_select_remove_option(index)"><i class="fa fa-close"></i></li>
														<li class="reorder"><i class="fa fa-reorder"></i></li>
													</ul>
												</div>
											</div>
										</li>
									</ul>
									<a class="button button-link button-blue m-b-3" v-on:click="field_select_add_option"><i class="fa fa-plus"></i> @lang('origami::field.options.title')</a>
								</div>

								<div class="radio-buttons">
									<label class="radio-button"><input type="radio" name="options[select][element]" v-model="field.options.select.element" value="radio">@lang('origami::field.options.radio')</label>
									<label class="radio-button"><input type="radio" name="options[select][element]" v-model="field.options.select.element" value="dropdown">@lang('origami::field.options.dropdown')</label>
								</div>
							</div>

							<div v-if="field.type=='date'">
								<div class="switch-checkbox">
									<label>
										<input type="checkbox" name="options[date][datetime]" v-model="field.options.date.datetime" v-bind:value="1">
										<div class="title">@lang('origami::field.date.add')</div>
										<div class="check">
											<div class="handle"></div>
										</div>
									</label>
								</div>
								<div class="switch-checkbox">
									<label>
										<input type="checkbox" name="options[date][disallow_past]" v-model="field.options.date.disallow_past" v-bind:value="1">
										<div class="title">@lang('origami::field.date.info.dissalow_past_dates')</div>
										<div class="check">
											<div class="handle"></div>
										</div>
									</label>
								</div>
								<div class="switch-checkbox">
									<label>
										<input type="checkbox" name="options[date][disallow_future]" v-model="field.options.date.disallow_future" v-bind:value="1">
										<div class="title">@lang('origami::field.date.info.dissalow_future_dates')</div>
										<div class="check">
											<div class="handle"></div>
										</div>
									</label>
								</div>

							</div>

							<div v-if="field.type=='module'">
								<div class="switch-checkbox">
									<label>
										<input type="checkbox" name="options[module][sortable]" v-model="field.options.module.sortable" v-bind:value="1">
										<div class="title">@lang('origami::global.sortable')</div>
										<div class="check">
											<div class="handle"></div>
										</div>
									</label>
								</div>
							</div>

							<div class="switch-checkbox" v-if="field.type!='checkbox' && field.type!='image' && field.type!='select' && field.type!='module' && field.type!='module' && !field.default">
								<label>
									<input type="checkbox" name="required" v-model="field.required" v-bind:value="1">
									<div class="title">@lang('origami::global.required')</div>
									<div class="check">
										<div class="handle"></div>
									</div>
								</label>
							</div>

						</div>

					</div>
				</div>
				<button type="submit" class="button m-t-3">{{ $field->id ? trans('origami::global.update') : trans('origami::global.create') }}</button>
				@if($field->id)
				<button type="button" class="button button-link" v-on:click="confirm('Are you absolutely sure you want to remove this field?','{{ origami_url('/modules/'.$module->uid.'/fields/'.$field->uid.'/remove') }}')">Remove field</button>
				@endif
			</form>
		</div>
	</div>
</div>

@endsection

@section('javascript')
<script>
	app.field = {
		type: '{{ origami_form($field, 'type') ?: 'text' }}',
		name: '{{ origami_form($field, 'name') }}',
		description: '{{ origami_form($field, 'description') }}',
		default: {{ origami_form($field, 'default') ?: 0 }},
		required: {{ origami_form($field, 'required') ?: 0 }},
		options: {
			textarea: {
				markdown: {{ !empty(origami_form($field, 'options')['textarea']['markdown']) ? origami_form($field, 'options')['textarea']['markdown'] : 0 }},
			},
			checkbox: {
				checked: {{ !empty(origami_form($field, 'options')['checkbox']['checked']) ? origami_form($field, 'options')['checkbox']['checked'] : 0 }},
			},
			select: {
				element: '{{ !empty(origami_form($field, 'options')['select']['element']) ? origami_form($field, 'options')['select']['element'] : 'radio' }}',
				options: {!! !empty(origami_form($field, 'options')['select']['options']) ? json_encode(origami_form($field, 'options')['select']['options']) : '[]' !!},
			},
			image: {
				multiple: {{ !empty(origami_form($field, 'options')['image']['multiple']) ? origami_form($field, 'options')['image']['multiple'] : 0 }},
				resize: {
					active: {{ !empty(origami_form($field, 'options')['image']['resize']['active']) ? origami_form($field, 'options')['image']['resize']['active'] : 0 }},
					width: {{ !empty(origami_form($field, 'options')['image']['resize']['width']) ? origami_form($field, 'options')['image']['resize']['width'] : 1000 }},
					height: {{ !empty(origami_form($field, 'options')['image']['resize']['height']) ? origami_form($field, 'options')['image']['resize']['height'] : 1000 }},
					crop: {{ !empty(origami_form($field, 'options')['image']['resize']['crop']) ? origami_boolToInt(origami_form($field, 'options')['image']['resize']['crop']) : 0 }},
				},
				thumbnail: {
					active: {{ !empty(origami_form($field, 'options')['image']['thumbnail']['active']) ? origami_form($field, 'options')['image']['thumbnail']['active'] : 0 }},
					width: {{ !empty(origami_form($field, 'options')['image']['thumbnail']['width']) ? origami_form($field, 'options')['image']['thumbnail']['width'] : 500 }},
					height: {{ !empty(origami_form($field, 'options')['image']['thumbnail']['height']) ? origami_form($field, 'options')['image']['thumbnail']['height'] : 500 }},
					crop: {{ !empty(origami_form($field, 'options')['image']['thumbnail']['crop']) ? origami_boolToInt(origami_form($field, 'options')['image']['thumbnail']['crop']) : 0 }},
				},
			},
			date: {
				datetime: {{ !empty(origami_form($field, 'options')['date']['datetime']) ? origami_form($field, 'options')['date']['datetime'] : 0 }},
				disallow_past: {{ !empty(origami_form($field, 'options')['date']['disallow_past']) ? origami_form($field, 'options')['date']['disallow_past'] : 0 }},
				disallow_future: {{ !empty(origami_form($field, 'options')['date']['disallow_future']) ? origami_form($field, 'options')['date']['disallow_future'] : 0 }},
			},
			module: {
				sortable: {{ !empty(origami_form($field, 'options')['module']['sortable']) ? origami_form($field, 'options')['module']['sortable'] : 0 }},
			},
		},
	}
</script>
@endsection
