<div class="mt-6">
    <div class="w-full flex">
        <div class="p-6">
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Инициатор (наименование подразделения заказчика)'))
                        ->name('initiator')
                    }}
                    {{Aire::textArea('bio', __('Цель / содержание закупки (обоснование необходимости закупки)'))
                        ->name('purchase_basis')
                        ->rows(3)
                        ->cols(40)
                    }}
                    {{Aire::textArea('bio', __('Описание предмета закупки (технические характеристики)'))
                        ->name('specification')
                        ->rows(3)
                        ->cols(40)
                    }}
                    {{Aire::dateTimeLocal('bio', __('Ожидаемый срок поставки'))
                        ->name('delivery_date')
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Наименование предмета закупки(товар, работа, услуги)'))
                        ->name('name')
                    }}
                    {{Aire::textArea('bio', __('Основания (план закупок, рапорт,распоряжение руководства)'))
                        ->name('basis')
                        ->rows(3)
                        ->cols(40)
                    }}
                    {{Aire::textArea('bio', __('Особые требования'))
                        ->name('separate_requirements')
                        ->rows(3)
                        ->cols(40)
                    }}
                    {{Aire::dateTimeLocal('bio',  __('Гарантийный срок качества товара (работ, услуг)'))
                        ->name('expire_warranty_date')
                    }}
                </div>
            </div>
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Планируемый бюджет закупки (сумма)'))
                        ->name('planned_price')
                        ->id('summa')
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Статья расходов по Бизнес плану'))
                        ->name('info_business_plan')
                    }}
                </div>

            </div>
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::input('bio', __('Эквивалентная Планируемая сумма'))
                        ->name('equal_planned_price')
                    }}
                </div>
            </div>
            <div class="flex items-baseline">
                <div class="mr-4 pt-2 pb-2 w-50">
                    {{Aire::textArea('bio', __('Информация о наличии в «Плане закупок» приобретаемых товаров'))
                        ->name('info_purchase_plan')
                        ->rows(3)
                        ->cols(40)
                    }}
                </div>
                <div class="pt-2 pb-2 w-50">
                    {{Aire::textArea('bio', __('Примечание для заказа'))
                        ->name('comment')
                        ->rows(3)
                        ->cols(40)
                    }}
                </div>
            </div>
        </div>
    </div>
    {{Aire::input()->name('user_id')->value(auth()->user()->id)->class('hidden')}}
    <div class="w-full text-right py-4 pr-10">
        <button type="submit"
        class="bg-green-500 hover:bg-green-700 p-2 transition duration-300 rounded-md text-white">
            {{ __('Отправить на подпись') }}
        </button>
        <button type="submit"
                class="bg-green-500 hover:bg-green-700 p-2 transition duration-300 rounded-md text-white">
            {{ __('Сохранить в черновик') }}
        </button>

    </div>
</div>
