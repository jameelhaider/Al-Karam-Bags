<?php

use App\Models\CloseMonths;
use App\Models\LoanEntries;
use Illuminate\Support\Carbon;
use App\Mail\DatabaseBackupMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\TypesController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\DealersController;
use App\Http\Controllers\DemandsController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CashReceivedController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CloseMonthController;
use App\Http\Controllers\IssuesController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\StickyNotesController;
use Symfony\Component\HttpFoundation\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('stock/parts/download-pdf', [StocksController::class, 'downloadpartspdf'])->name('stock.parts.download.pdf');
Route::post('stock/tools/download-pdf', [StocksController::class, 'downloadtoolspdf'])->name('stock.tools.download.pdf');
Route::post('cash/received/download-pdf', [CashReceivedController::class, 'downloadpdf'])->name('cash.received.download.pdf');

Route::post('demands/download/pdf', [DemandsController::class, 'downloadpdf'])->name('demands.download.pdf');
Route::get('dealers/download-pdf', [DealersController::class, 'downloadpdf'])->name('dealers.download.pdf');

Route::post('accounts/download-pdf', [AccountsController::class, 'downloadpdf'])->name('accounts.download.pdf');


Route::get('demands/delete/all/{type}', [DemandsController::class, 'deleteall'])->name("demand.delete.all");
Route::post('/demand/delete-selected', [DemandsController::class, 'deleteSelected'])->name('demand.delete.selected');




Route::get('admin/copy-row-parts', function (Request $request) {
    $types = DB::table('types')
        ->where('type', 'parts')
        ->get();
    $partscompanies = DB::table('companies')
        ->where('type', 'parts')
        ->get();
    $output = null;
    if ($request->filled('company_id') && $request->filled('type_id')) {

        $stocks = DB::table('stocks')
            ->join('companies', 'stocks.company_id', '=', 'companies.id')
            ->join('models', 'stocks.model_id', '=', 'models.id')
            ->select(
                'companies.name as company_name',
                'models.model as model_name',
                'stocks.type_name',
                'stocks.sale_price'
            )
            ->where('stocks.type', 'parts')
            ->where('stocks.company_id', $request->company_id)
            ->where('stocks.type_name', function ($q) use ($request) {
                $q->select('name')
                    ->from('types')
                    ->where('id', $request->type_id);
            })
            ->where('stocks.qty', '>', 0)
            ->orderBy('models.model')
            ->get();

        $output = $stocks->map(function ($stock) {
            $model = str_pad($stock->model_name, 30, ' ', STR_PAD_RIGHT);
            $price = number_format($stock->sale_price);
            return "{$model}  Rs. {$price}";
        })->implode("\n");
    }
    return view('copyrawdata', compact(
        'types',
        'partscompanies',
        'output'
    ));
});







Route::get('admin/generate-pdf', function () {

    if (Gate::allows('is_admin')) {
        $types = DB::table('types')->where('type', 'parts')->get();
        $accounts = DB::table('accounts')->orderby('created_at', 'asc')->get();
        $partscompanies = DB::table('companies')->where('type', 'parts')->get();
        $toolscompanies = DB::table('companies')->where('type', 'tools')->get();
        return view('generatepdf', compact('types', 'partscompanies', 'toolscompanies', 'accounts'));
    } else {
        return abort(401);
    }
})->name('index.generate.pdf');



Route::get('/send-database-backup', function () {
    $date = Carbon::now()->format('d_M_y_h_i_A');
    $filename = "Backup_Al_Karam_Bags_$date.sql";
    $tables = DB::select('SHOW TABLES');
    $tableNames = array_map('current', $tables);
    $sql = '';

    foreach ($tableNames as $table) {
        $createTableQuery = DB::select("SHOW CREATE TABLE `$table`");
        $sql .= $createTableQuery[0]->{'Create Table'} . ";\n\n";
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $values = array_map(function ($value) {
                return DB::connection()->getPdo()->quote($value);
            }, (array) $row);

            $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
        }
        $sql .= "\n\n";
    }
    Mail::to(['jameelhaider047@gmail.com', 'rana.awais.9110@gmail.com'])
        ->send(new DatabaseBackupMail($sql, $filename));
    return redirect()->back()->with('success', 'Database backup sent to email successfully.');
})->name('send.database.backup');


Route::get('/start-month', function () {
    $currentMonth = Carbon::now()->format('Y-m');
    $existingEntry = CloseMonths::where('month', $currentMonth)->first();
    if ($existingEntry) {
        return response()->json(['error' => 'Already Started'], 400);
    }
    $closeMonth = new CloseMonths();
    $closeMonth->month = $currentMonth;
    $closeMonth->save();
    return response()->json(['message' => 'Month Start Successfully', 'month' => $currentMonth]);
})->name('cronjob.startmonth');


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect('/admin');
    });
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/admin');
    });


    Route::get('/export-database', function () {
        $date = Carbon::now()->format('d_M_y_h_i_A');
        $filename = "Backup_Al_Karam_Bags_$date.sql";
        $tables = DB::select('SHOW TABLES');
        $tableNames = array_map('current', $tables);
        $sql = '';

        foreach ($tableNames as $table) {
            $createTableQuery = DB::select("SHOW CREATE TABLE `$table`");
            $sql .= $createTableQuery[0]->{'Create Table'} . ";\n\n";
            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $values = array_map(function ($value) {
                    return DB::connection()->getPdo()->quote($value);
                }, (array) $row);

                $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
            }
            $sql .= "\n\n";
        }

        return Response::make($sql, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    })->name('export.database');






    Route::get('/company/{id}/models', [HomeController::class, 'getModels']);



    Route::group(['prefix' => 'admin'], function () {
        Route::get('/change-password', [HomeController::class, 'changepassword'])->name("change.password");
        Route::post('/update-password', [HomeController::class, 'updatepassword'])->name("update.password");


        Route::post('/update-show', [HomeController::class, 'updateshow'])->name("update.show");


        Route::get('/', function () {
            if (Gate::allows('is_admin')) {


                // Counts
                $totalaccounts = DB::table('accounts')->count();
                $totaldemands = DB::table('demands')->count();
                $totaltoolsdemands = DB::table('demands')->where('type', 'tools')->count();
                $totalpartsdemands = DB::table('demands')->where('type', 'parts')->count();
                $totaltools = DB::table('stocks')->where('type', 'tools')->count();
                $totalparts = DB::table('stocks')->where('type', 'parts')->count();
                $totalstockitems = DB::table('stocks')->count();
                //total stocks
                $totalavailitems = DB::table('stocks')->whereColumn('qty', '>', 'alert_qty')->count();
                $totaloutitems = DB::table('stocks')->where('qty', '0')->count();
                $totallowitems = DB::table('stocks')->whereColumn('qty', 'alert_qty')->count();
                //parts stocks
                $totalavailitemsparts = DB::table('stocks')
                    ->where('type', 'parts')
                    ->whereColumn('qty', '>', 'alert_qty')
                    ->count();
                $totaloutitemsparts = DB::table('stocks')
                    ->where('type', 'parts')
                    ->where('qty', '0')
                    ->count();
                $totallowitemsparts = DB::table('stocks')
                    ->where('type', 'parts')
                    ->whereColumn('qty', 'alert_qty')
                    ->count();
                //tools stocks
                $totalavailitemstools = DB::table('stocks')
                    ->where('type', 'tools')
                    ->whereColumn('qty', '>', 'alert_qty')
                    ->count();
                $totaloutitemstools = DB::table('stocks')
                    ->where('type', 'tools')
                    ->where('qty', '0')
                    ->count();
                $totallowitemstools = DB::table('stocks')
                    ->where('type', 'tools')
                    ->whereColumn('qty', 'alert_qty')
                    ->count();


                // Revenues
                $today = Carbon::today();
                $startOfWeek = Carbon::now()->startOfWeek();
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();
                $endOfWeek = Carbon::now()->endOfWeek();
                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();
                $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
                $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();
                $todayRevenue = DB::table('invoices')
                    ->whereDate('created_at', $today)
                    ->where('status', '!=', 'Returned')
                    ->sum('profit');
                $thisWeekRevenue = DB::table('invoices')
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->where('status', '!=', 'Returned')
                    ->sum('profit');
                $thisMonthRevenue = DB::table('invoices')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('status', '!=', 'Returned')
                    ->sum('profit');
                $thisYearRevenue = DB::table('invoices')
                    ->whereBetween('created_at', [$startOfYear, $endOfYear])
                    ->where('status', '!=', 'Returned')
                    ->sum('profit');
                $overallRevenue = DB::table('invoices')
                    ->where('status', '!=', 'Returned')
                    ->sum('profit');
                $previousMonthRevenue = DB::table('invoices')
                    ->whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])
                    ->where('status', '!=', 'Returned')
                    ->sum('profit');

                // Expenses for This Month
                $totalExpensesthismonth = DB::table('expenses')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->sum('ammount');

                // Profit this month after expenses
                $totalProfitthismonthAfterExpenses = $thisMonthRevenue - $totalExpensesthismonth;

                $totalrass = DB::table('stocks')
                    ->select(DB::raw('SUM(qty * purchase_price) as total'))
                    ->value('total');

                $totaltodaysales = DB::table('invoices')
                    ->whereDate('created_at', $today)
                    ->where('status', '!=', 'Returned')
                    ->sum('total_bill');

                $totalthisweeksales = DB::table('invoices')
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->where('status', '!=', 'Returned')
                    ->sum('total_bill');
                $totalthismonthsales = DB::table('invoices')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('status', '!=', 'Returned')
                    ->sum('total_bill');
                $totalprevmonthsales = DB::table('invoices')
                    ->whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])
                    ->where('status', '!=', 'Returned')
                    ->sum('total_bill');
                $totalthisyearsales = DB::table('invoices')
                    ->whereBetween('created_at', [$startOfYear, $endOfYear])
                    ->where('status', '!=', 'Returned')
                    ->sum('total_bill');
                $totaloverallsales = DB::table('invoices')
                    ->where('status', '!=', 'Returned')
                    ->sum('total_bill');
                $totalrem = DB::table('accounts')
                    ->where('prev_balance', '>', 0)
                    ->sum('prev_balance');


                return view('admin', compact(
                    'totalaccounts',
                    //parts
                    'totalavailitemsparts',
                    'totaloutitemsparts',
                    'totallowitemsparts',
                    //tools
                    'totalavailitemstools',
                    'totaloutitemstools',
                    'totallowitemstools',

                    'totalavailitems',
                    'totaloutitems',
                    'totallowitems',
                    'totaltoolsdemands',
                    'totalpartsdemands',
                    'totaldemands',
                    'totaltools',
                    'totalparts',
                    'todayRevenue',
                    'thisWeekRevenue',
                    'thisYearRevenue',
                    'overallRevenue',
                    'thisMonthRevenue',
                    'totalExpensesthismonth',
                    'previousMonthRevenue',
                    'totalrass',
                    'totalProfitthismonthAfterExpenses',
                    'totalstockitems',
                    'totaltodaysales',
                    'totalthisweeksales',
                    'totalthismonthsales',
                    'totalprevmonthsales',
                    'totalthisyearsales',
                    'totaloverallsales',
                    'totalrem'
                ));
            } else {
                return abort(401);
            }
        });




        //Stocks
        Route::group(['prefix' => 'stocks'], function () {
            //CRUD
            Route::post('/submit', [StocksController::class, 'submit'])->name("submit.stock");
            Route::get('/{type}/create', [StocksController::class, 'create'])->name("create.stock");
            Route::get('/{type}/edit/{id}', [StocksController::class, 'edit'])->name("stock.edit");
            Route::post('/update/{id}', [StocksController::class, 'update'])->name("update.stock");
            Route::post('/addtodemand', [StocksController::class, 'addtodemand'])->name("add.stock.demand");
            Route::post('/addtoinvoice', [StocksController::class, 'addtoinvoice'])->name("add.item.invoice");

            Route::get('/delete/{id}', [StocksController::class, 'delete'])->name("stock.delete");
            //index
            Route::get('/{type}', [StocksController::class, 'index'])->name("index.stock");
            //models
            Route::get('/{type}/models', [ModelsController::class, 'index'])->name("index.model");
            Route::get('/{type}/models/create', [ModelsController::class, 'create'])->name("create.model");
            Route::get('/{type}/models/edit/{id}', [ModelsController::class, 'edit'])->name("model.edit");
            //companies
            Route::get('/{type}/companies', [CompaniesController::class, 'index'])->name("index.company");
            Route::get('/{type}/companies/create', [CompaniesController::class, 'create'])->name("create.company");
            Route::get('/{type}/companies/edit/{id}', [CompaniesController::class, 'edit'])->name("company.edit");
            //types
            Route::get('/{type}/types', [TypesController::class, 'index'])->name("index.type");
            Route::get('/{type}/types/create', [TypesController::class, 'create'])->name("create.type");
            Route::get('/{type}/types/edit/{id}', [TypesController::class, 'edit'])->name("type.edit");




            Route::get('/sold-out', [StocksController::class, 'index2'])->name("index2.stock");
            // view
            Route::get('/{id}/view', [StocksController::class, 'view'])->name("stock.view");
        });


        //closemonth
        Route::group(['prefix' => 'close-month'], function () {
            //CRUD
            Route::post('/submit', [CloseMonthController::class, 'submit'])->name("submit.closemonth");
            Route::get('/create', [CloseMonthController::class, 'create'])->name("create.closemonth");
            Route::post('/update/{id}', [CloseMonthController::class, 'update'])->name("update.closemonth");
            //index
            Route::get('/', [CloseMonthController::class, 'index'])->name("index.closemonth");

            Route::post('/submit/expence', [ExpensesController::class, 'submit'])->name("expense.submit");
            Route::get('/view/{month_id}', [ExpensesController::class, 'view'])->name("expense.view");
            Route::get('/delete/expense/{id}', [ExpensesController::class, 'delete'])->name("expense.delete");
        });

        //accounts
        Route::group(['prefix' => 'accounts'], function () {
            //CRUD
            Route::post('/submit', [AccountsController::class, 'submit'])->name("submit.account");
            Route::get('/create', [AccountsController::class, 'create'])->name("create.account");
            Route::get('/edit/{id}', [AccountsController::class, 'edit'])->name("account.edit");
            Route::post('/update/{id}', [AccountsController::class, 'update'])->name("update.account");
            //index
            Route::get('/', [AccountsController::class, 'index'])->name("index.account");
            Route::get('/view/{id}/purchase-history', [AccountsController::class, 'viewpurchase'])->name("account.purchase.history");

            Route::get('/{id}/ledger', [AccountsController::class, 'ledger'])->name("account.ledger");
            Route::get('/{id}/details', [AccountsController::class, 'details'])->name("account.details");
        });



        //jobs
        Route::group(['prefix' => 'jobs'], function () {
            //CRUD
            Route::post('/submit', [JobsController::class, 'submit'])->name("submit.job");
            Route::get('/create', [JobsController::class, 'create'])->name("create.job");
            Route::get('/edit/{id}', [JobsController::class, 'edit'])->name("job.edit");
            Route::post('/update/{id}', [JobsController::class, 'update'])->name("update.job");
            //index
            Route::get('/', [JobsController::class, 'index'])->name("index.job");
            // view
            Route::get('view/{job_id}', [JobsController::class, 'view'])->name("view.job");
        });

        //issues
        Route::group(['prefix' => 'issues'], function () {
            //CRUD
            Route::post('/submit', [IssuesController::class, 'submit'])->name("submit.issue");
            Route::get('/create', [IssuesController::class, 'create'])->name("create.issue");
            Route::get('/edit/{id}', [IssuesController::class, 'edit'])->name("issue.edit");
            Route::post('/update/{id}', [IssuesController::class, 'update'])->name("update.issue");
            //index
            Route::get('/', [IssuesController::class, 'index'])->name("index.issue");
        });

        Route::get('/sale-history', [AccountsController::class, 'salehistory'])->name("sale.history");
        Route::get('/return-history', [AccountsController::class, 'returnhistory'])->name("return.history");
        Route::get('/stock-stats/{type}', [StocksController::class, 'stockstats'])->name("stockstats");

        //cash received
        Route::group(['prefix' => 'cash-received'], function () {
            //CRUD
            Route::post('/submit', [CashReceivedController::class, 'submit'])->name("submit.cash");
            Route::get('/create', [CashReceivedController::class, 'create'])->name("create.cash");
            Route::get('/edit/{id}', [CashReceivedController::class, 'edit'])->name("cash.edit");
            Route::get('/delete/{id}', [CashReceivedController::class, 'delete'])->name("cash.delete");
            Route::get('/view/receipt/{id}', [CashReceivedController::class, 'viewreceipt'])->name("cash.view.receipt");
            Route::post('/update/{id}', [CashReceivedController::class, 'update'])->name("update.cash");
            //index
            Route::get('/', [CashReceivedController::class, 'index'])->name("index.cash");
        });


        //sticky notes
        Route::group(['prefix' => 'sticky-notes'], function () {
            //CRUD
            Route::post('/submit', [StickyNotesController::class, 'submit'])->name("submit.stickynote");
            Route::get('/create', [StickyNotesController::class, 'create'])->name("create.stickynote");
            Route::get('/edit/{id}', [StickyNotesController::class, 'edit'])->name("stickynote.edit");
            Route::post('/update/{id}', [StickyNotesController::class, 'update'])->name("update.stickynote");
            Route::get('/delete/{id}', [StickyNotesController::class, 'delete'])->name("stickynote.delete");
            //pin / unpin
            Route::get('/stickynote/{id}/pin', [StickyNotesController::class, 'pin'])->name('stickynote.pin');
            Route::get('/stickynote/{id}/unpin', [StickyNotesController::class, 'unpin'])->name('stickynote.unpin');

            //index
            Route::get('/', [StickyNotesController::class, 'index'])->name("index.stickynote");
        });


        //Demands
        Route::group(['prefix' => 'demands'], function () {
            //CRUD
            Route::post('/submit', [DemandsController::class, 'submit'])->name("submit.demand");
            Route::get('{type}/create', [DemandsController::class, 'create'])->name("create.demand");
            Route::get('{type}/edit/{id}', [DemandsController::class, 'edit'])->name("demand.edit");
            Route::post('/update/{id}', [DemandsController::class, 'update'])->name("update.demand");
            Route::get('/delete/{id}', [DemandsController::class, 'delete'])->name("demand.delete");

            //index
            Route::get('/{type}', [DemandsController::class, 'index'])->name("index.demand");
        });

        //profits
        Route::group(['prefix' => 'profit-stats'], function () {
            Route::get('/', [ExpensesController::class, 'stats'])->name("stats.profit");
        });

        //dealers
        Route::group(['prefix' => 'dealers'], function () {
            //CRUD
            Route::post('/submit', [DealersController::class, 'submit'])->name("submit.dealer");
            Route::get('/create', [DealersController::class, 'create'])->name("create.dealer");
            Route::get('/edit/{id}', [DealersController::class, 'edit'])->name("dealer.edit");
            Route::post('/update/{id}', [DealersController::class, 'update'])->name("update.dealer");
            Route::get('/delete/{id}', [DealersController::class, 'delete'])->name("dealer.delete");
            //index
            Route::get('/', [DealersController::class, 'index'])->name("index.dealer");
        });

        //Invoices
        Route::group(['prefix' => 'invoices'], function () {
            //view
            Route::get('/view/{id}', [InvoicesController::class, 'view'])->name("invoice.view");
            Route::get('/view/return/{id}', [InvoicesController::class, 'returnview'])->name("invoice.return.view");
            //return
            Route::get('/return/{id}/{invoice_to}/{acc_id}', [InvoicesController::class, 'returninvoice'])->name("return.invoice");

            Route::get('/remove/{id}/{invoice_id}', [InvoicesController::class, 'removeitem'])->name("remove.invoice.item");


            Route::post('/return/invoice-item', [InvoicesController::class, 'returninvoiceitem'])->name("return.invoice.item");


            //index
            Route::get('/', [InvoicesController::class, 'index'])->name("index.invoice");
            //return
            Route::get('/return', [InvoicesController::class, 'returnindex'])->name("index.return.invoice");
        });
        Route::get('/invoice/make', [InvoicesController::class, 'make'])->name("invoice.make");
        Route::post('/invoice/save', [InvoicesController::class, 'save'])->name("save.invoice");
        //return
        Route::post('/invoice/return/save', [InvoicesController::class, 'savereturn'])->name("save.invoice.return");

        //return
        Route::get('/invoice/make/return', [InvoicesController::class, 'makereturn'])->name("invoice.make.return");

        //models
        Route::group(['prefix' => 'models'], function () {
            //CRUD
            Route::post('/submit', [ModelsController::class, 'submit'])->name("submit.model");
            // Route::get('/create', [ModelsController::class, 'create'])->name("create.model");
            // Route::get('/edit/{id}', [ModelsController::class, 'edit'])->name("model.edit");
            Route::post('/update/{id}', [ModelsController::class, 'update'])->name("update.model");
            Route::get('/delete/{id}', [ModelsController::class, 'delete'])->name("model.delete");
            //index
            // Route::get('/', [ModelsController::class, 'index'])->name("index.model");
        });


        //types
        Route::group(['prefix' => 'types'], function () {
            //CRUD
            Route::post('/submit', [TypesController::class, 'submit'])->name("submit.type");
            // Route::get('/create', [TypesController::class, 'create'])->name("create.model");
            // Route::get('/edit/{id}', [TypesController::class, 'edit'])->name("type.edit");
            Route::post('/update/{id}', [TypesController::class, 'update'])->name("update.type");
            Route::get('/delete/{id}', [TypesController::class, 'delete'])->name("model.type");
            //index
            // Route::get('/', [TypesController::class, 'index'])->name("index.type");
        });




        //Companies
        Route::group(['prefix' => 'companies'], function () {
            //CRUD
            Route::post('/submit', [CompaniesController::class, 'submit'])->name("submit.company");
            // Route::get('/create', [CompaniesController::class, 'create'])->name("create.company");
            // Route::get('/edit/{id}', [CompaniesController::class, 'edit'])->name("company.edit");
            Route::post('/update/{id}', [CompaniesController::class, 'update'])->name("update.company");
            Route::get('/delete/{id}', [CompaniesController::class, 'delete'])->name("company.delete");
            //index
            // Route::get('/', [CompaniesController::class, 'index'])->name("index.company");
        });
    });
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
