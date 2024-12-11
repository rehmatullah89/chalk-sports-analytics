<div class="card-body">
    <table class="table table-hover" id="all_predictions">
        <thead class="thead-dark">
        <tr>
            <th style="text-align: right;">Week Number</th>
            <th>Team 1</th>
            <th>Team 2</th>
            <th>Winner</th>
            <th style="text-align: right;">Team 1 Score</th>
            <th style="text-align: right;">Team 2 Score</th>
            <th style="text-align: right;">Team 1 Predicted</th>
            <th style="text-align: right;">Team 2 Predicted</th>
            <th>Team Predicted Win</th>
            <th style="text-align: right;">Actual Mov</th>
            <th style="text-align: right;">Predicted Mov</th>
            <th style="text-align: right;">Actual Pts</th>
            <th style="text-align: right;">Predicted Pts</th>
            <th style="text-align: right;">Winner Correct</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data2 as $key => $object)
            <tr>
                <td style="text-align: right;">{{ $object->week_number }}</td>
                <td>{{ $object->t_name}}</td>
                <td>{{ $object->t2_name }}</td>
                <td>{{ $object->t3_name }}</td>
                <td style="text-align: right;">{{ $object->team_1_score }}</td>
                <td style="text-align: right;">{{ $object->team_2_score }}</td>
                <td style="text-align: right;">{{ $object->team_1_p }}</td>
                <td style="text-align: right;">{{ $object->team_2_p }}</td>
                <td>{{ $object->name }}</td>
                <td style="text-align: right;">{{ $object->actual_mov }}</td>
                <td style="text-align: right;">{{ $object->predicted_mov }}</td>
                <td style="text-align: right;">{{ $object->act_pts }}</td>
                <td style="text-align: right;">{{ $object->p_pts }}</td>
                <td style="text-align: right;">{{ $object->correct_winner }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
