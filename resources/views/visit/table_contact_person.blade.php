<div class="table-responsive">
    <table class="table table-bordered ">
        <thead class="text-center">
            <tr>
                <th rowspan="2">วันที่</th>
                <th rowspan="2">ชื่อ-นามสกุล</th>
                <th rowspan="2">เพศ</th>
                <th rowspan="2">ประเภท</th>
                <th colspan="4">ช่องทางการมีเพศสัมพันธ์</th>
                <th colspan="4">การใช้ถุงยาง</th>
                <th rowspan="2">การติดตามคู่สัมผัส</th>
                <th rowspan="2">Actions</th>
            </tr>
            <tr>
               
                <th>Vergina</th>
                <th>Mouth</th>
                <th>Penis</th>
                <th>Anus</th>
                <th>ใช้</th>
                <th>ไม่ใช้</th>
                <th>แตก/หลุด</th>
                <th>ไม่แตก/หลุด</th>
               
            </tr>

        </thead>
         <tbody>
            @foreach ($contactperson as $k1 => $item)
                <?php $sex_condoms = $item->sex_condom();
                $count = count($sex_condoms);
                ?>
                @foreach ($sex_condoms as $key => $sex_condom)
                    <tr key="{{ $k1 }}">
                        @if ($key == 0)
                            <td rowspan="{{ $count }}">{{ $item->date }}</td>
                            <td rowspan="{{ $count }}">{{ $item->name_surname }}</td>
                            <td rowspan="{{ $count }}">{{ $item->cpsex() }}</td>
                            <td rowspan="{{ $count }}">{{ $item->type() }}</td>
                        @endif


                        <td key="{{ $key }}">{{ $sex_condom['vagina_mt'] }}</td>
                        <td>{{ $sex_condom['mouth_mt'] }} </td>
                        <td>{{ $sex_condom['penis_mt'] }}</td>
                        <td>{{ $sex_condom['anus_mt'] }}</td>
                        <td>{{ $sex_condom['use_cd'] }}</td>
                        <td>{{ $sex_condom['unuse_cd'] }} </td>
                        <td>{{ $sex_condom['brea_slip_cd'] }}</td>
                        <td>{{ $sex_condom['unbrea_slip_cd'] }}</td>


                        @if ($key == 0)
                            <td rowspan="{{ $count }}">
                                {{ $item->trush_tracks ? App\Models\ContactPerson::$touchTracingOption[$item->trush_tracks] : '' }}
                            </td>

                            <td rowspan="{{ $count }}">

                                <a href="{{ route('contact-person.edit', ['contact_person' => $item->id, 'page' => $_GET['page']]) }}"
                                    title="Edit ContactPerson" class="btn btn-primary btn-sm"><i class="fa fa-pencil"
                                        aria-hidden="true"></i></a>
                                <div style="display: none;">
                                    <form method="POST" action="{{ route('contact-person.destroy', $item->id) }}"
                                        accept-charset="UTF-8">
                                    </form>
                                </div>
                                <form method="POST" action="{{ route('contact-person.destroy', ['contact_person'=>$item->id, 'page' => $_GET['page']]) }}"
                                    accept-charset="UTF-8">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete ContactPerson"
                                        onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash"
                                            aria-hidden="true"></i></button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</div>

