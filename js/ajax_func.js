function SearchData(url,success_function){
    var params = $('form').eq(0).serializeArray();
    $.ajax({
        url:url,
        type:'GET',
        data:params,
        dataType:'json',
        // beforeSend:function(){LoadingMask('#waitloading')},
        // complete:function(){ClearMask('#waitloading','#search-data');}
    })
    .then(
        function(data){success_function(data)},
        function(err){alert('連線失敗請重新再試!!');}
    )
}
function LoadingMask(load_id){
    setTimeout(()=>{
        $(load_id).append(
            `<div class="btn btn-orange" role="button">
                <span class="spinner-border" role="status" aria-hidden="true"></span>
                <span class="d-block">Loading...</span>
            </div>`
        )
        $(load_id).removeClass("d-none");
    },0)
}

function ClearMask(load_id,search_area){
    setTimeout(()=>{
        $(load_id).empty()
        $(load_id).addClass("d-none");
        $(search_area).removeClass("d-none");
    },0)
}