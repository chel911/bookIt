@extends('layouts.app') @section('content')
<div class="container-fluid">
    <div class="row">
        @include('notes.layouts.sidebar')
        <style>
            label {
                color: #6f6d6d;
                font-weight: 600;
                font-size: 13px;
            }
        </style>
        <div class="col">
            <div class="container py-3">
                <div class="d-flex flex-row mb-3">
                    <p style="font-weight: 700; font-size: 30px;">
                        Dashboard
                    </p>
                    @include('layouts.notification')

                </div>

                <hr style="border-top: 1px solid #00000023;" class="mt-0" />

                <p style="font-weight: 700; font-size: 22px;">
                    Analytics overview
                </p>
                <div class="container mr-auto ml-3 mt-4">
                    <div class="row">
                        <div class="col mr-5" style="height: 150px; border-radius: 10px; background: #dfe2fd;">
                            <div class="container py-3">
                                <div class="row mb-1"><img src="/images/icons/dashboard_note_icon.svg" alt="" /></div>
                                <div class="row mb-1 pb-0"><span style="font-weight: 700; font-size: 30px;"> {{$notes->count()}} </span></div>
                                <div class="row"><span style="font-weight: 500; font-size: 14px; color: #6f6d6d;">Notes</span></div>
                            </div>
                        </div>
                        <div class="col mr-5" style="height: 150px; border-radius: 10px; background: #FBDFDF;">
                            <div class="container py-3">
                                <div class="row mb-1"><img src="/images/icons/dashboard_book_icon.svg" alt="" /></div>
                                <div class="row mb-1 pb-0"><span style="font-weight: 700; font-size: 30px;">{{$books->count()}}</span></div>
                                <div class="row"><span style="font-weight: 500; font-size: 14px; color: #6f6d6d;">Books</span></div>
                            </div>
                        </div>
                        <div class="col mr-5" style="height: 150px; border-radius: 10px; background: #DFFBEB;">
                            <div class="container py-3">
                                <div class="row mb-1"><img src="/images/icons/dashboard_task_icon.svg" alt="" /></div>
                                <div class="row mb-1 pb-0"><span style="font-weight: 700; font-size: 30px;">{{$tasks->count()}}</span></div>
                                <div class="row"><span style="font-weight: 500; font-size: 14px; color: #6f6d6d;">Tasks</span></div>
                            </div>
                        </div>
                        <div class="col mr-5" style="height: 150px; border-radius: 10px; background: #FFFDDB;">
                            <div class="container py-3">
                                <div class="row mb-1"><img src="/images/icons/dashboard_average_icon.svg" alt="" /></div>
                                <div class="row mb-1 pb-0"><span style="font-weight: 700; font-size: 30px;">@if ($books->count())
                                    {{number_format(($notes->count()/$books->count()), 2, '.', ',')}}
                                @else
                                    0
                                @endif
                                    </span></div>
                                <div class="row"><span style="font-weight: 500; font-size: 14px; color: #6f6d6d;">Avg. of notes <br> per book </span></div>
                            </div>
                        </div>
                        <div class="col mr-5" style="height: 150px; border-radius: 10px; background: #FFFDDB;">
                            <div class="container py-3">
                                <div class="row mb-1"><img src="/images/icons/dashboard_average_icon.svg" alt="" /></div>
                                <div class="row mb-1 pb-0"><span style="font-weight: 700; font-size: 30px;"> @if ($books->count())
                                    {{number_format(($tasks->count()/$books->count()), 2, '.', ',')}}
                                @else
                                    0
                                @endif </span></div>
                                <div class="row"><span style="font-weight: 500; font-size: 14px; color: #6f6d6d;">Avg. of tasks <br> per book </span></div>
                            </div>
                        </div>
                    </div>
{{--  --}}
                    <div class="row mt-4">
                        <div class="col mr-5" style="height: 150px; border-radius: 10px; background: #DFFBEB;">
                            <div class="container py-2 pl-3 pr-2">
                                <div class="row">
                                    <div class="col">
                                         
                                    <div class="row mb-1 pb-0"><span style="font-weight: 700; font-size: 24px;">Tasks</span></div>
                                    <div class="row"><img src="/images/dashboard_tasks.svg" alt="" style="width:70%; height:auto;"></div>
                                    </div>
                                    <div class="col">
                                        <div class="row">&nbsp;</div>
                                        <div class="row mb-1 pb-0 pl-3" ><span  style="font-weight: 700; font-size: 30px;">{{$tasks->where('status', 'done')->count()}}</span></div>
                                        <div class="row"><span style="font-weight: 600; font-size: 11px; color: #6f6d6d;">Completed <br> tasks</span></div>
                                    </div>
                                    <div class="col mr-4">
                                        <div class="row">&nbsp;</div>
                                        <div class="row mb-1 pb-0 pl-4" ><span  style="font-weight: 700; font-size: 30px;">
                                            {{$tasks_count}}
                                        </span></div>
                                        <div class="row"><span style="font-weight: 600; font-size: 11px; color: #6f6d6d;">Completed tasks <br> before due date</span></div>
                                    </div>
                                    <div class="col ml-2 mr-0">
                                        <div class="row">&nbsp;</div>
                                        <div class="row mb-2 pb-0 pl-1" ><span  style="font-weight: 700; font-size: 27px;">
                                        @if ($tasks->where('status', 'done')->count())
                                        @if (($tasks_count) / $tasks->where('status', 'done')->count()==1)
                                            100%
                                        @else
                                            
                                       
                                        {{number_format((($tasks_count) / $tasks->where('status', 'done')->count() *100), 2, '.', ',')}}%
                                        @endif
                                        @else
                                        0%
                                        @endif 
                                       
                                        </span></div>
                                        <div class="row"><span style="font-weight: 600; font-size: 11px; color: #6f6d6d;">Rate of completed
                                              tasks</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col mr-5" style="height: 150px; border-radius: 10px; background: #FBDFDF;">
                            <div class="container py-2 pl-3 pr-2">
                                <div class="row">
                                    <div class="col mr-4">
                                         
                                    <div class="row mb-1 pb-0"><span style="font-weight: 700; font-size: 24px;">Reading</span></div>
                                    <div class="row mt-2"><img src="/images/dashboard_books.svg" alt="" style="width:80%; height:auto;"></div>
                                    </div>
                                    <div class="col">
                                        <div class="row">&nbsp;</div>
                                        <div class="row mb-1 pb-0 pl-3" ><span  style="font-weight: 700; font-size: 30px;"> {{$books->where('read', '1')->count()}} </span></div>
                                        <div class="row"><span style="font-weight: 600; font-size: 11px; color: #6f6d6d;">Books read</span></div>
                                    </div>
                                    <div class="col mr-4">
                                        <div class="row">&nbsp;</div>
                                        <div class="row mb-1 pb-0 pl-2" ><span  style="font-weight: 700; font-size: 30px;">0 <span style="font-size:25px; ">min</span></span></div>
                                        <div class="row"><span style="font-weight: 600; font-size: 11px; color: #6f6d6d;">Avg. reading time <br>
                                            per day</span></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                       
                    </div>
                </div>
                <p style="font-weight: 700; font-size: 22px;" class="mt-4">
                    Tasks activity
                </p>
                {{--  --}}
            
                {{--  --}}
                @if ($account->account_type === "premium" || ($account->account_type==="free" && $account->end_date) )
                <div class="container ">
                    <canvas class="mx-auto" id="myChart" style="width:100%;max-width:1000px"></canvas>
 
                </div>
                @else
                <div class="container ">
                    <div style=" width:950px; height:500px; background-image:url('/images/graph2.png'); background-size: cover; filter:blur(3.5px); "  >
                       
                    
                    </div>  
                    <div style="position: absolute; bottom:250px; left:380px;" class="text-center">
                        <p class="font-weight-bold mb-3" style="font-size:17px;">Upgrade to premium to see this graph  </p>
                           <a role="button" aria-disabled="true"  name="upgrade" class="btn btn-lg btn-primary text-white  "
                                    style="background-color:# ;font-weight:700; text-decoration:none; z-index:100;" href="{{route('upgrade')}}"  >Upgrade</a>
                    </div>
                    
                </div>
                @endif
               
            </div>
        </div>
    </div>
    <script>

let tasks_histories = {!! json_encode($tasks_histories) !!};
// console.log(tasks_histories);
         let not_started = [];
         let in_progress = [];
         let done = [];

        tasks_histories.forEach(task_history => {
             if(task_history.new_status === "not started")
            {
                not_started.push(task_history);
            }
            if(task_history.new_status === "in progress")
            {
                in_progress.push(task_history);
            }
            if(task_history.new_status === "done")
            {
                done.push(task_history);
            }

        });
   
             const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
    
        const labels = [];

        var lastday = function(y,m){
        return  new Date(y, m +1, 0).getDate();
        }

        let k = new Date().getDate();
 
        let M;
        let sm, em ;

        if(k!==1)
        {
             M = lastday(new Date().getYear(), new Date().getMonth()  - 1);
            sm = monthNames[new Date().getMonth()  - 1];
            em = monthNames[new Date().getMonth()];
        }
        else {
             M = lastday(new Date().getYear(), new Date().getMonth());
             sm = monthNames[new Date().getMonth() ];
                em = monthNames[new Date().getMonth()  ];
        }
        


        let l = 0;
        for(let i=k+1; i<=M; i++)
        {
            labels[l] = i;
            l++;
        }
        for(let i=1; i<=k; i++)
        {
            labels[l] = i;
            l++
        }
        console.log(labels.indexOf(11));
        let data_not_started = [];  
        let data_in_progress = [];  
        let data_done = [];  

        

               for(let i=1; i<31; i++)
                {
                    var count =0;
                    for (let index = 0; index < not_started.length; index++) {
                    let date = new Date(not_started[index].created_at);
                 
                        if(date.getDate()==i){
                            count++;
                        }
                        data_not_started[labels.indexOf(i)] = count;  
                }                
                }

                for(let i=1; i<31; i++)
                {
                    var count =0;
                    for (let index = 0; index < in_progress.length; index++) {
                    let date = new Date(in_progress[index].created_at);
                        if(date.getDate()==i){
                            count++;
                        }
                        data_in_progress[labels.indexOf(i)] = count;  
                }                
                }

                for(let i=1; i<31; i++)
                {
                    var count =0;
                    for (let index = 0; index < done.length; index++) {
                    let date = new Date(done[index].created_at);
                        if(date.getDate()==i){
                            count++;
                        }
                        data_done[labels.indexOf(i)] = count;  
                }                
                }

                let labels2 = [];
        l=0;
        for(let i=k+1; i<=M; i++)
        {
            labels2[l] = i+" "+sm;
            l++;
        }
        for(let i=1; i<=k; i++)
        {
            labels2[l] = i+" "+em;
            l++
        }


        let myChart = document.getElementById('myChart');

            const data = {
            labels: labels2,
            datasets: [{
                label: 'not started',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: data_not_started,
                tension: 0.1
            },{
                label: 'in progress',
                backgroundColor: 'rgb(25, 99, 132)',
                borderColor: 'rgb(25, 99, 132)',
                data: data_in_progress,
                tension: 0.1
            },
            {
                label: 'done',
                backgroundColor: 'rgb(6, 186, 99)',
                borderColor: 'rgb(6, 186, 99)',
                data: data_done,
                tension: 0.1
            }
            ], 
            };
   
      
      let graph = new Chart(myChart, {
        type:'line',  
        data,
        options: {
        plugins: {
            title: {
                display: true,
             },
            legend: {
                display: true,
            }
        }
    }
      });
      /**************************************************/
        //     let myChart2 = document.getElementById('myChart2');

        // const data2 = {
        // labels: labels2,
        // datasets: [{
        //     label: 'not started',
        //     backgroundColor: 'rgb(255, 99, 132)',
        //     borderColor: 'rgb(255, 99, 132)',
        //     data: data_not_started,
        //     tension: 0.1
        // },{
        //     label: 'in progress',
        //     backgroundColor: 'rgb(25, 99, 132)',
        //     borderColor: 'rgb(25, 99, 132)',
        //     data: data_in_progress,
        //     tension: 0.1
        // },
        // {
        //     label: 'done',
        //     backgroundColor: 'rgb(6, 186, 99)',
        //     borderColor: 'rgb(6, 186, 99)',
        //     data: data_done,
        //     tension: 0.1
        // }
        // ], 
        // };


        // let graph2 = new Chart(myChart2, {
        // type:'line',  
        // data2,
        // options: {
        // plugins: {
        // title: {
        //     display: true,
        // },
        // legend: {
        //     display: true,
        // }
        // }
        // }
        // });
      /**************************************************/
 
      </script>
</div>

    @endsection
    

