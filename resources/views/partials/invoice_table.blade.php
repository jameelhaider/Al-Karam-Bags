<div>
    <h3 class="text-center text-dark fw-bold mt-2 mb-2">{{ $title }}</h3>
    <div class="card p-2 mb-0">
        @if ($items->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="font-size:10px;" class="text-dark fw-bold">#</th>
                            <th style="font-size:10px;" class="text-dark fw-bold">Name</th>

                            @if ($showPurchase ?? false)
                                <th style="font-size:10px;width:10%" class="text-dark fw-bold purchase-price-header">
                                    Purchase</th>
                            @endif

                            <th style="font-size:10px;width:12%" class="text-dark fw-bold">Sale</th>
                            <th style="font-size:10px;width:12%" class="text-dark fw-bold text-center">Avail Qty</th>
                            <th style="font-size:10px;width:12%" class="text-dark fw-bold">Qty</th>
                            <th style="font-size:10px;width:15%" class="text-dark fw-bold">Final</th>
                            <th style="font-size:10px;" class="text-dark fw-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <label for="checkbox-{{ $item->id }}" class="checkbox-label">
                                        <input type="checkbox" id="checkbox-{{ $item->id }}"
                                            name="items[{{ $item->id }}][selected]" value="1"
                                            class="large-checkbox" {{ $item->qty > 0 ? '' : 'disabled' }}>
                                    </label>
                                </td>
                                <td>
                                    <label for="checkbox-{{ $item->id }}"
                                        style="cursor:pointer;font-size:15px;
                                                  color:{{ $item->qty > 0 ? 'rgb(1,149,1)' : 'rgb(253,27,27)' }}"
                                        class="checkbox-label fw-bold">
                                        {{ $item->name }}
                                    </label>
                                </td>

                                @if ($showPurchase ?? false)
                                    <td class="text-dark fw-normal purchase-price-cell">
                                        {{ 'Rs. ' . $item->purchase_price }}
                                    </td>
                                @endif

                                <td class="fw-bold text-dark">{{ 'Rs. ' . number_format($item->sale_price) }}</td>
                                <td class="text-center text-dark">{{ $item->qty }}</td>

                                <td>
                                    <input type="number" name="items[{{ $item->id }}][qty]"
                                        class="form-control qty" value="1" min="1"
                                        max="{{ $item->qty }}" data-id="{{ $item->id }}">
                                </td>
                                <td>
                                    <input type="number" name="items[{{ $item->id }}][final_price]"
                                        class="form-control final_price" value="{{ $item->sale_price }}"
                                        min="{{ $item->purchase_price }}" data-id="{{ $item->id }}">
                                </td>
                                <td>
                                    <span class="total_price text-dark" id="total_{{ $item->id }}">
                                        {{ 'Rs.' . $item->sale_price }}
                                    </span>
                                    <input type="hidden" name="items[{{ $item->id }}][price]"
                                        value="{{ $item->sale_price }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="font-size:10px;" class="text-dark fw-bold">#</th>
                            <th style="font-size:10px;width:15%" class="text-dark fw-bold">Name</th>
                            <th style="font-size:10px;width:10%" class="text-dark fw-bold">Sale</th>
                            <th style="font-size:10px;width:12%" class="text-dark fw-bold text-center">Avail Qty</th>
                            <th style="font-size:10px;width:10%" class="text-dark fw-bold">Qty</th>
                            <th style="font-size:10px;" class="text-dark fw-bold">Final</th>
                            <th style="font-size:10px;" class="text-dark fw-bold">Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <h4 class="h4 text-center text-dark fw-bold mt-2">No {{ $title }} Found!</h4>
        @endif
    </div>
</div>
