<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\GameLevelGauntlet;
use App\Models\GameLevel;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class GameLevelGauntletController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new GameLevelGauntlet(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('level1');
            $grid->column('level2');
            $grid->column('level3');
            $grid->column('level4');
            $grid->column('level5');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new GameLevelGauntlet(), function (Show $show) {
            $show->field('id');
            $show->field('level1');
            $show->field('level2');
            $show->field('level3');
            $show->field('level4');
            $show->field('level5');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new GameLevelGauntlet(), function (Form $form) {
            $form->display('id');

            $levels = GameLevel::all(['id', 'name'])->pluck('name', 'id');
            $form->select('level1')->options($levels);
            $form->select('level2')->options($levels);
            $form->select('level3')->options($levels);
            $form->select('level4')->options($levels);
            $form->select('level5')->options($levels);

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
