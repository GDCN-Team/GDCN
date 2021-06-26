<?php

namespace App\Admin\Controllers;

use App\Models\GameLevel;
use App\Models\GameLevelPack;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class GameLevelPackController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new GameLevelPack(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('levels');
            $grid->column('stars');
            $grid->column('coins');
            $grid->column('difficulty');
            $grid->column('text_color');
            $grid->column('bar_color');
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
        return Show::make($id, new GameLevelPack(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('levels');
            $show->field('stars');
            $show->field('coins');
            $show->field('difficulty');
            $show->field('text_color');
            $show->field('bar_color');
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
        return Form::make(new GameLevelPack(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->multipleSelect('levels')
                ->options(GameLevel::all(['id', 'name'])->pluck('name', 'id'))
                ->saving(function (array $levels) {
                    return implode(',', $levels);
                });

            $form->slider('stars')->options([
                'min' => 1,
                'max' => 10
            ]);

            $form->slider('coins')->options([
                'min' => 0,
                'max' => 2
            ]);

            $form->select('difficulty')->options([
                0 => 'Auto',
                1 => 'Easy',
                2 => 'Normal',
                3 => 'Hard',
                4 => 'Harder',
                5 => 'Insane',
                6 => 'Easy Demon',
                7 => 'Medium Demon',
                8 => 'Hard Demon',
                9 => 'Insane Demon',
                10 => 'Extreme Demon'
            ]);

            $form->color('text_color')->rgb();
            $form->color('bar_color')->rgb();

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
