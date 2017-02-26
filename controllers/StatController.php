<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\controllers\ProtectedController;
use app\models\Category;
use app\models\Outlay;
use app\models\Income;
use app\models\Pickup;
use app\models\Product;

class StatController extends ProtectedController
{
    public function actionReport()
    {
        $thisMonth = date("Y-m-01");
        $categories = Category::getCategories();
        $outlays = Outlay::getOutlaysByMonth($thisMonth);
        $incomes = Income::getIncomesByMonth($thisMonth);
        $pickups = Pickup::getPickupsByMonth($thisMonth);
        $totalPickup = Pickup::getTotalByMonth($thisMonth);
        $residuePurchase = Product::getResiduePurchase($thisMonth);
        $residueDebt = 0;
        return $this->render('report', [
            'categories' => $categories,
            'outlays' => $outlays,
            'pickups' => $pickups,
            'incomes' => $incomes,
            'totalPickup' => $totalPickup,
            'cashbox' => $this->calcCashbox(),
            'residuePurchase' => $residuePurchase,
            'residueDebt' => $residueDebt,
        ]);
    }
    
    public function actionOutlay()
    {
        $thisMonth = date("Y-m-01");
        $error = "";
        if (isset($_POST)) {
            if (isset($_POST['action']) && $_POST['action'] == 'delete') {
                if (!empty($_POST['id'])) {
                    $outlay = Outlay::findOne([
                        'id' => $_POST['id']
                    ]);
                    $outlay->delete();
                    Income::recalcIncomes($thisMonth);
                }
            } else if (isset($_POST['action']) && $_POST['action'] == 'pickup') {
                if (!empty($_POST['amount'])) {
                    if (is_numeric($_POST['amount'])) {
                        $pickup = new Pickup();
                        $pickup->pickup_date = $thisMonth;
                        $pickup->amount = $_POST['amount'];
                        $pickup->save();
                    } else {
                        $error = "Неверное значение в поле 'Сумма'";
                    }
                } else {
                    $error = "Все поля должны быть заполнены";
                }
            } else {
                if (isset($_POST['id'])) {
                    if (!empty($_POST['type']) && !empty($_POST['amount'])) {
                        if (is_numeric($_POST['amount'])) {
                            $outlay = Outlay::findOne([
                                'id' => $_POST['id']
                            ]);
                            $outlay->type = trim($_POST['type']);
                            $outlay->amount = $_POST['amount'];
                            $outlay->save();
                            Income::recalcIncomes($thisMonth);
                        } else {
                            $error = "Неверное значение в поле 'Сумма'";
                        }
                    } else {
                        $error = "Все поля должны быть заполнены";
                    }
                } else {
                    if (!empty($_POST['type']) && !empty($_POST['amount'])) {
                        if (is_numeric($_POST['amount'])) {
                            $outlay = new Outlay();
                            $outlay->amount = $_POST['amount'];
                            $outlay->type = trim($_POST['type']);
                            $outlay->outlay_date = $thisMonth;
                            $outlay->save();
                            Income::recalcIncomes($thisMonth);
                        } else {
                            $error = "Неверное значение в поле 'Сумма'";
                        }
                    } else {
                        $error = "Все поля должны быть заполнены";
                    }
                }
            }
        } else {
            $error = "Ошибка передачи данных";
        }
        
        $outlays = Outlay::getOutlaysByMonth($thisMonth);
        $incomes = Income::getIncomesByMonth($thisMonth);
        $pickups = Pickup::getPickupsByMonth($thisMonth);
        $totalPickup = Pickup::getTotalByMonth($thisMonth);
        $residuePurchase = Product::getResiduePurchase($thisMonth);
        $residueDebt = 0;
        return $this->renderPartial('outlay', [
            'outlays' => $outlays,
            'incomes' => $incomes,
            'pickups' => $pickups,
            'error' => $error,
            'totalPickup' => $totalPickup,
            'cashbox' => $this->calcCashbox(),
            'residuePurchase' => $residuePurchase,
            'residueDebt' => $residueDebt,
        ]);
    }
    
    protected function calcCashbox()
    {
        $thisMonth = date("Y-m-01");
        $income = Income::getIncomeByMonth(Income::GROUP_REVENUE, $thisMonth);
        $outlays = Outlay::getTotalByMonth($thisMonth);
        $totalPickup = Pickup::getTotalByMonth($thisMonth);
        $cashbox = $income->amount - $outlays - $totalPickup;
        return $cashbox;
    }
}