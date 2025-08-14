<link rel="stylesheet" type="text/css" href="/css/select2.min.css">
<div class="card-body">
<div class="form-group {{ $errors->has('house_no') ? 'has-error' : ''}}">
    <label for="house_no" class="control-label">{{ 'บ้านเลขที่ (ตามบัตร)' }}</label>
    <input class="form-control" name="house_no" type="text" id="house_no" value="{{ isset($address->house_no) ? $address->house_no : ''}}" required>
    {!! $errors->first('house_no', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    <label for="address" class="control-label">{{ 'บ้านเลขที่ (ปัจจุบัน)' }}</label>
    <input class="form-control" name="address" type="text" id="address" value="{{ isset($address->address) ? $address->address : ''}}" >
    {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('province') ? 'has-error' : ''}}">
    <label for="province" class="control-label">{{ 'Province' }}</label>
    {{-- <input class="form-control" name="province" type="text" id="province" value="{{ isset($address->province) ? $address->province : ''}}" > --}}

    <select class="form-control" name="province" id="province" >
     
      @if (isset($address->province))
        <option value='{{$address->province}}' selected="selected">{{Baraear\ThaiAddress\Models\Province::find($address->province)->name}}</option>
      @else
        <option value='72'>เชียงใหม่</option>
      @endif
    </select>

    {!! $errors->first('province', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('district') ? 'has-error' : ''}}">
    <label for="district" class="control-label">{{ 'District' }}</label>
    {{-- <input class="form-control" name="district" type="text" id="district" value="{{ isset($address->district) ? $address->district : ''}}" > --}}
    
    <select class="form-control" name="district" id="district" >
      
      @if (isset($address->district))
        <option value='{{$address->district}}' selected="selected">{{Baraear\ThaiAddress\Models\District::find($address->district)->name}}</option>
      @else
      <option value='871'>เมืองเชียงใหม่</option>
      @endif
    </select>

    {!! $errors->first('district', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('sub_district') ? 'has-error' : ''}}">
    <label for="sub_district" class="control-label">{{ 'Sub District' }}</label>
    <select class="form-control" name="sub_district" id="sub_district" >
        <!-- <option value='0'>-- เลือก ตำบล --</option> -->
        @if (isset($address->sub_district))
        <option value='{{$address->sub_district}}' selected="selected">{{Baraear\ThaiAddress\Models\SubDistrict::find($address->sub_district)->name}}</option>
      @else
      <option value='0'>-- เลือก ตำบล --</option>
      @endif
    </select>
    {!! $errors->first('sub_district', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group {{ $errors->has('zipcode') ? 'has-error' : ''}}">
    <label for="zipcode" class="control-label">{{ 'Zipcode' }}</label>
    <input class="form-control" name="zipcode" type="text" id="zipcode" value="{{ isset($address->zipcode) ? $address->zipcode : ''}}" readonly>
    {!! $errors->first('zipcode', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
</div>
<script src="/js/select2.min.js" type="text/javascript"></script>

    <!-- Script -->
    <script type="text/javascript">

        // CSRF Token
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){
    
          $( "#sub_district" ).select2({
            ajax: { 
              url: "{{route('sub-district.search')}}",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  _token: CSRF_TOKEN,
                  search: params.term , // search term
                  district: $("#district").val()
                };
              },
              processResults: function (data) {
                return {
                    results:  $.map(data.data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                }
              },
              cache: true
            }
    
          });

          $( "#district" ).select2({
            ajax: { 
              url: "{{route('district.search')}}",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  _token: CSRF_TOKEN,
                  search: params.term, // search term
                  province: $("#province").val()
                };
              },
              processResults: function (data) {
                return {
                    results:  $.map(data.data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                }
              },
              cache: true
            }
    
          });

          $( "#province" ).select2({
            ajax: { 
              url: "{{route('province.search')}}",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  _token: CSRF_TOKEN,
                  search: params.term // search term
                };
              },
              processResults: function (data) {
                return {
                    results:  $.map(data.data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                }
              },
              cache: true
            }
    
          });
          
          function getZipcode(){
         
            if($("#sub_district").val() != 0 && $("#district").val() != 0 && $("#province").val() != 0 ){
             
              $.post("{{route('postal-code.getPostal')}}",
              {
                _token: CSRF_TOKEN,
                sub_district_id: $("#sub_district").val(),
                district_id: $("#district").val(),
                province_id: $("#province").val(),
              },
              function(data, status){
                // alert("Data: " + data + "\nStatus: " + status);
                
                $("#zipcode").val(data['data'][0]['code'])
              });

                // $.ajax({ 
                //   url: "{{route('postal-code.getPostal')}}",
                //   type: "POST",
                //   dataType: 'json',
                //   data: function (params) {
                //     return {
                //       _token: CSRF_TOKEN,
                //       sub_district_id: $("#sub_district").val(),
                //       district_id: $("#district").val(),
                //       province_id: $("#province").val(),
                      
                //     };
                //   },
                //   processResults: function (data) {
                //     console.log(data)
                //   },
                //   cache: true
                // })
        
             
            }
          }
          $( "#sub_district" ).change(getZipcode);
          $( "#district" ).change(getZipcode);
          $( "#province" ).change(getZipcode);
          
    
        });
        </script>
