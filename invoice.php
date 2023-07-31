<?php 

	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');
?>
    <link href="./assets/css/invoice.css" rel="stylesheet"></head>
    <div class="container-fluid" style="padding:5rem 2.5rem;">
        <div class="row justify-content-center">
            <div class="col-md-10 card bg-light shadow">
                <div class="row no-gutters">
                    <div class="col-md-4 totalAmount">
                        <h2></h2>
                    </div>
                    <div class="col-md-8">
                        <div class="card-header">
                            <h3 class="card-title m-0">發票明細(INVOICE)</h3>
                        </div>
                        <ul class="invoice list-group">
                            <li class="list-group-item">發票號碼</li>
                            <li class="list-group-item">載具編號</li>
                            <li class="list-group-item">開立日期</li>
                            <li class="list-group-item">隨機碼</li>
                        </ul>
                    </div>
                    <div class="col-md-12 table-responsive-md">
                        <div class="card-header">
                            <h3 class="card-title m-0">品項</h3>
                        </div>
                        <table id="details" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">名稱</th>
                                    <th scope="col">數量</th>
                                    <th scope="col">單價</th>
                                    <th scope="col">金額</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

	  <script src="js/ajax_func.js"></script>
    <script>
      const renderId = [$('.totalAmount h2'), $('.invoice li'), $('#details tbody')];
      const page = {
          invoice:(data)=>{
            const keys = Object.keys(data);
            const filter_invoiceInfo = Object.entries(data).filter((v,i)=>{return v[0] != keys[0] && v[0] != keys[5] && v[0]!=keys[6]});
            const invoiceInfo = filter_invoiceInfo.map((v,i)=>{
              return `<li class="list-group-item">${v[1]}</li>`
            });

            const details = data.Details.map((v,i)=>{
              return `<tr>
                        <th scope="row">${v.description}</th>
                        <td>${v.quantity}</td>
                        <td>${v.unitprice}</td>
                        <td>${v.amount}</td>
                    </tr>`
            });

            renderId[0].append(`NT$ ${data.totalAmount}`)
            $.each(invoiceInfo,(i,v)=>{
              renderId[1].eq(i).after(v);
            });
            renderId[2].append(details);
            renderId[1].next().addClass('text-primary');
          }
      }

      $(document).ready(function(){
          SearchData('api/invoice.json',page.invoice);
      });


    </script>

<?php include('footer_layout.php'); ?>