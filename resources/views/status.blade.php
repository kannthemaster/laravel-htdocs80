<div class="table-responsive">
    <table class="table table-info">
        <thead>
            <tr>
                <th>ลงทะเบียนเบอร์ 1</th><th>ห้องตรวจเบอร์ 2</th><th>ห้องตรวจเบอร์ 8</th><th>ห้องเจาะเลือด/ฉีดยาเบอร์ 4</th>
            </tr>
        </thead>
        <tbody>
            
  
            <tr>
                <td>{{ App\Models\Visit::count(1)}}</td><td>{{ App\Models\Visit::count(2) }}</td><td>{{ App\Models\Visit::count(8) }}</td><td>{{ App\Models\Visit::count(3) }}</td>
                

            </tr>
      
        </tbody>
    </table>
    @include('sweetalert::alert')
</div>