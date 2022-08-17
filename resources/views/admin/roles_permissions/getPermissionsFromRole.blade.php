<div class="kt-checkbox-list">
    <label class="kt-checkbox kt-checkbox--solid kt-checkbox--success">
        <input type="checkbox" id="check_all"/>
        Select All
        <span></span></label>
</div>
<br>
<br>

<div class="form-group row">
    @foreach ($all_controllers as $key => $row)

        <div class="col-md-4">
            <hr>
            <div class="kt-checkbox-list">
                <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                    <input type="checkbox" class="check_all_sub" id="{{$key}}">
                    {{$key}}
                    <span></span>
                </label>
            </div>
            <br>
            <div class="{{$key}}">
                @foreach ($row as $route)
                    <div class="col-md-6">
                        <div class="kt-checkbox-list">
                            <label class="kt-checkbox kt-checkbox--solid kt-checkbox--success">
                                <input type="hidden" name="permissions[]" value="{{$route->id}}">
                                <input type="checkbox" id="perm{{$route->id}}" name="permissions_checked[]"
                                       value="{{$route->id}}"
                                       @if(in_array($route->id, $emp_permissions)) checked @endif>
                                {{$route->name}}
                                <span></span></label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
<script>
    function selectAll() {
        $("#check_all").on('click', function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(".check_all_sub").click(function () {
            $('div.' + this.id + ' input:checkbox').prop('checked', this.checked);
        });
    };
    selectAll();
</script>