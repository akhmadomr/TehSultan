<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    const STATUS_WAITING = 'waiting';
    const STATUS_VALIDATED = 'validated';
    const STATUS_REJECTED = 'rejected';

    const SHIFT_PAGI = 'pagi';
    const SHIFT_SIANG = 'siang';

    protected $fillable = [
        'outlet_id',
        'user_id',
        'type',
        'status',
        'shift',
        'validated_by',
        'validated_at'
    ];

    protected $attributes = [
        'status' => self::STATUS_WAITING // Set default status
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'shift' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function financialItems(): HasMany
    {
        return $this->hasMany(FinancialItem::class);
    }

    public function validateAgainstOtherReport()
    {
        $query = self::query()
            ->where('outlet_id', $this->outlet_id)
            ->where('shift', $this->shift)
            ->whereDate('created_at', $this->created_at)
            ->where('id', '!=', $this->id);

        if ($this->type === 'stock') {
            $otherReport = $query->where('type', 'financial')->first();
        } else {
            $otherReport = $query->where('type', 'stock')->first();
        }

        if (!$otherReport) {
            return [
                'status' => false, 
                'message' => 'Tidak ditemukan laporan yang sesuai untuk validasi'
            ];
        }

        return ['status' => true, 'message' => 'Laporan yang sesuai ditemukan'];
    }

    public function validateCupUsage()
    {
        if ($this->type !== 'stock') {
            return ['status' => true, 'message' => null];
        }

        $stockReport = $this;
        $financialReport = self::where('outlet_id', $this->outlet_id)
            ->where('shift', $this->shift)
            ->whereDate('created_at', $this->created_at)
            ->where('type', 'financial')
            ->first();

        if (!$financialReport) {
            return [
                'status' => false, 
                'message' => 'Tidak ditemukan laporan keuangan yang sesuai'
            ];
        }

        // Get used cups from stock report
        $cupItem = $stockReport->items()->where('item_name', 'Cup')->first();
        $usedCups = $cupItem ? $cupItem->used_stock : 0;

        // Calculate total beverages sold with individual sums
        $beverages = ['The', 'Lemon Tea', 'Milktea', 'Free cup'];
        $totalBeveragesSold = 0;
        foreach ($beverages as $beverage) {
            $totalBeveragesSold += $financialReport->financialItems()
                ->where('item_name', $beverage)
                ->sum('quantity');
        }

        if ($usedCups !== $totalBeveragesSold) {
            return [
                'status' => false,
                'message' => "Ketidaksesuaian penggunaan cup: Laporan stok menunjukkan {$usedCups} cup digunakan, tetapi laporan keuangan menunjukkan {$totalBeveragesSold} minuman terjual"
            ];
        }

        return ['status' => true, 'message' => null];
    }

    public function validateTeaUsage()
    {
        if ($this->type !== 'stock') {
            return ['status' => true, 'message' => null];
        }

        $cupItem = $this->items()->where('item_name', 'Cup')->first();
        $teaItem = $this->items()->where('item_name', 'Teh')->first();

        if (!$cupItem || !$teaItem) {
            return [
                'status' => false, 
                'message' => 'Data cup atau teh tidak ditemukan'
            ];
        }

        $usedCups = $cupItem->used_stock;
        $usedTea = $teaItem->used_stock;

        $upperLimit = round($usedCups / 35, 2);
        $lowerLimit = round($usedCups / 60, 2);

        if ($usedTea < $lowerLimit || $usedTea > $upperLimit) {
            return [
                'status' => false,
                'message' => "Penggunaan teh ({$usedTea}) di luar batas yang diperbolehkan (min: {$lowerLimit}, max: {$upperLimit})"
            ];
        }

        return ['status' => true, 'message' => null];
    }

    public function validateStockUsage()
    {
        if ($this->type !== 'stock') {
            return ['status' => true, 'message' => null];
        }

        $stockReport = $this;
        $financialReport = self::where('outlet_id', $this->outlet_id)
            ->where('shift', $this->shift)
            ->whereDate('created_at', $this->created_at)
            ->where('type', 'financial')
            ->first();

        if (!$financialReport) {
            return [
                'status' => false, 
                'message' => 'Tidak ditemukan laporan keuangan yang sesuai'
            ];
        }

        $stockItems = ['Pentol', 'Tahu', 'Urat', 'Puyuh'];
        $errors = [];

        foreach ($stockItems as $itemName) {
            $stockItem = $stockReport->items()->where('item_name', $itemName)->first();
            $usedStock = $stockItem ? $stockItem->used_stock : 0;

            $financialQuantity = $financialReport->financialItems()
                ->where('item_name', $itemName)
                ->sum('quantity');

            if ($usedStock != $financialQuantity) {
                $errors[] = "Ketidaksesuaian {$itemName}: Stok terpakai {$usedStock}, terjual {$financialQuantity}";
            }
        }

        if (!empty($errors)) {
            return [
                'status' => false,
                'message' => implode(', ', $errors)
            ];
        }

        return ['status' => true, 'message' => null];
    }

    public function validateCashDifference()
    {
        if ($this->type !== 'financial') {
            return ['status' => true, 'message' => null];
        }

        $difference = $this->difference ?? 0;

        if (abs($difference) > 20000) {
            return [
                'status' => false,
                'message' => "Selisih kas (Rp " . number_format($difference, 0, ',', '.') . ") melebihi batas normal (± Rp 20.000)"
            ];
        }

        return ['status' => true, 'message' => null];
    }

    public function getValidationMessages()
    {
        $messages = [];

        $reportMatch = $this->validateAgainstOtherReport();
        if (!$reportMatch['status']) {
            $messages[] = $reportMatch['message'];
        }

        $cupValidation = $this->validateCupUsage();
        if (!$cupValidation['status']) {
            $messages[] = $cupValidation['message'];
        }

        $teaValidation = $this->validateTeaUsage();
        if (!$teaValidation['status']) {
            $messages[] = $teaValidation['message'];
        }

        $stockValidation = $this->validateStockUsage();
        if (!$stockValidation['status']) {
            $messages[] = $stockValidation['message'];
        }

        $cashValidation = $this->validateCashDifference();
        if (!$cashValidation['status']) {
            $messages[] = $cashValidation['message'];
        }

        // Add default message if no issues found
        if (empty($messages)) {
            $messages[] = 'Tidak ada yang mencurigakan';
        }

        return $messages;
    }
}





