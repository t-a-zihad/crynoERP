<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Invoice</title>
		<style>
			/* reset */

			*
			{
				border: 0;
				box-sizing: content-box;
				color: inherit;
				font-family: inherit;
				font-size: inherit;
				font-style: inherit;
				font-weight: inherit;
				line-height: inherit;
				list-style: none;
				margin: 0;
				padding: 0;
				text-decoration: none;
				vertical-align: top;
			}




			/* table */

			table { font-size: 75%; table-layout: fixed; width: 100%; }
			table { border-collapse: collapse; }
			th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
			th, td { border-radius: 0.25em; border-style: solid; }
			th { background: #EEE; border-color: #BBB; }
			td { border-color: #DDD; }

			/* page */

			html { font: 16px/1 'Open Sans', sans-serif; overflow: auto; padding: 0.5in; }
			html { background: #999; cursor: default; }

			body { box-sizing: border-box; height: 11in; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; }
			body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }

			/* header */

			header { margin: 0 0 3em; }
			header:after { clear: both; content: ""; display: table; }

			header h1 { background: #000; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
			header address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
			header address p { margin: 0 0 0.25em; font-size: medium}
            header address p b{font-weight: bold}
			header span, header img { display: block; float: right; }
			header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
			header img { max-height: 100%; max-width: 100%; }
			header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }

			/* article */

			article, article address, table.meta, table.inventory { margin: 0 0 3em; }
			article:after { clear: both; content: ""; display: table; }
			article h1 { clip: rect(0 0 0 0); position: absolute; }

			article address { float: left; font-size: 14px; width: 45% }
            article address h5{margin-bottom: 10px; font-weight: Bold; border-bottom: .5px solid #999}

			/* table meta & balance */

			table.meta, table.balance { float: right; width: 45%; }
			table.meta:after, table.balance:after { clear: both; content: ""; display: table; }

			/* table meta */

			table.meta th { width: 20%; }
			table.meta td { width: 60%; }

			/* table items */

			table.inventory { clear: both; width: 100%; }
			table.inventory th { font-weight: bold; text-align: center; }

            table.inventory th:nth-child(1) { width: 50%; }
			table.inventory th:nth-child(2) { width: 15%; }
			table.inventory th:nth-child(3) { width: 15%; }
			table.inventory th:nth-child(4) { width: 5%; }
			table.inventory th:nth-child(5) { text-align: right; width: 15%; }

			table.inventory td:nth-child(1) { width: 50%; }
			table.inventory td:nth-child(2) { width: 15%; text-align: Center;}
			table.inventory td:nth-child(3) { text-align: center; width: 15%; }
			table.inventory td:nth-child(4) { text-align: center; width: 5%; }
			table.inventory td:nth-child(5) { text-align: right; width: 15%; }


			/* table balance */

			table.balance th, table.balance td { width: 50%; }
			table.balance td { text-align: right; }

			/* aside */

			aside h1 { border: none; border-width: 0 0 1px; margin: 0 0 1em;text-align: center; }
			aside h1 { border-color: #999; border-bottom-style: solid; }
			aside p {text-align: right}



			@media print {
				* { -webkit-print-color-adjust: exact; }
				html { background: none; padding: 0; }
				body { box-shadow: none; margin: 0; }
				span:empty { display: none; }
				.add, .cut { display: none; }
			}

			@page { margin: 0; }
		</style>

	</head>
	<body>

        @php
            $booksTotalPrice = $order->orderedBooks->sum(function($book) {
                    return $book->unit_price * $book->qty;
                });

            $deliveryCharge = $order->delivery_charge ?? 0;

            $discount = $order->discount ?? 0;

            $grandTotal = $booksTotalPrice + $deliveryCharge - $discount;
        @endphp

		<header>
			<address>
				<p><b>Customer Name: </b>{{ $order->customer_name }}</p>
                <p><b>Mobile No: </b>{{ $order->phone_number }}</p>

			</address>
			<span><img alt="Crynoverse" src="{{ asset('src\images\cryno-long-logo.png') }}"></span>
		</header>
		<article>
			<h1>Recipient</h1>
			<address >
                <h5>Shipping Address:</h5>

				<p>{{ $order->shipping_address }}</p>
			</address>
			<table class="meta">
				<tr>
					<th><span >Invoice #</span></th>
					<td><span >{{ $order->order_id }}</span></td>
				</tr>
				<tr>
					<th><span >Date</span></th>
					<td><span >{{ $order->created_at->format('F j, Y') }}</span></td>
				</tr>
			</table>
			<table class="inventory">
				<thead>
					<tr>
						<th><span >Book</span></th>
						<th><span >Binding Type</span></th>
						<th><span >Unit Price (BDT)</span></th>
						<th><span >Qty</span></th>
						<th><span >Price (BDT)</span></th>
					</tr>
				</thead>
				<tbody>
                    @foreach ($order->orderedBooks as $book)
                        <tr>
                            <td><span >{{$book->book_name}} @if ($book->book_author) by {{$book->book_author}} @endif</span></td>
                            <td><span >{{$book->binding_type}}</span></td>
                            <td><span >{{$book->unit_price}}</span></td>
                            <td><span >{{$book->qty}}</span></td>
                            <td><span>{{$book->unit_price * $book->qty}}</span></td>
                        </tr>
                    @endforeach
				</tbody>
			</table>
			<table class="balance">
				<tr>
					<th><span >Total (BDT)</span></th>
					<td><span>{{$booksTotalPrice}}</span></td>
				</tr>
				<tr>
					<th><span >Delivery Charge (BDT)</span></th>
					<td><span >{{$deliveryCharge}}</span></td>
				</tr>
                @if ($discount>0)
                <tr>
					<th><span >Discount (BDT)</span></th>
					<td><span data-prefix>-</span><span>{{$discount}}</span></td>
				</tr>
                @endif
				<tr>
					<th><span >Grand Total (BDT)</span></th>
					<td><span>{{$grandTotal}}</span></td>
				</tr>
			</table>
		</article>
		<aside>
			<h1><span >Thanks For Ordering | Happy Reading</span></h1>
			<div >
				<p><a href="https://www.facebook.com/crynobooks">https://www.facebook.com/crynobooks</a></p>
			</div>
		</aside>
	</body>
</html>
