@extends(Config::get('oxygen/core::layout'))

@section('content')

<?php

    use Oxygen\Core\Html\Header\Header;
    use Oxygen\Core\Html\Form\Footer;
    use Oxygen\Core\Html\Form\EditableField;

    $header = Header::fromBlueprint(
        $blueprint,
        Lang::get('oxygen/preferences::ui.update.title', ['name' => $schema->getTitle()])
    );

    $header->setBackLink(URL::route($blueprint->getRouteName('getView'), Preferences::parentGroup($schema->getKey())));

?>

<!-- =====================
            HEADER
     ===================== -->

<div class="Block">
    {{ $header->render()}}
</div>

<?php

    echo Form::open([
        'route' => [$blueprint->getRouteName('putUpdate'), $schema->getKey()],
        'method' => 'PUT',
        'class' => 'Form--sendAjax Form--warnBeforeExit Form--submitOnKeydown Form--themes'
    ]);

    $titles = [];
?>

<div class="Row--layout Row--equalCells">
    <?php
        foreach(Theme::all() as $theme):
            $itemHeader = new Header($theme->getName(), ['span' => 'oneThird'], Header::TYPE_BLOCK);
            $itemHeader->addClass('Link-cursor');
            if($theme == Theme::current()) {
                $itemHeader->setSubtitle('(current)');
            }
            $itemHeader->setIndex($theme->getKey());
            if($theme->hasImage()) {
                $itemHeader->setContent('<img src="' . $theme->getImage() . '">');
            } else {
                $itemHeader->setContent('<div class="Icon-container"><span class="Icon Icon--gigantic Icon--light Icon-picture-o"></span></div>');
            }
            echo $itemHeader->render();
        endforeach;
    ?>
</div>

<div class="Block js-hide">
    <?php
        $field = $schema->getField('theme');
        $editableField = new EditableField($field, Theme::getCurrentKey());
        echo $editableField->render();
    ?>
</div>

<div class="Block js-hide">
    <?php
        if(!isset($footer)) {
            $footer = new Footer([
                [
                    'route' => $blueprint->getRouteName('getView'),
                    'label' => Lang::get('oxygen/preferences::ui.update.close')
                ],
                [
                    'type' => 'submit',
                    'label' => Lang::get('oxygen/preferences::ui.update.submit')
                ]
            ]);
        }
        echo $footer->render();
    ?>
</div>

<?php echo Form::close(); ?>

@stop

<?php Event::listen('oxygen.layout.page.after', function() { ?>

    <script>
        var Oxygen = Oxygen || {};
        Oxygen.load = Oxygen.load || [];
        Oxygen.load.push(function() {
            $("[data-index]").on("click", function() {
                $('[name="theme"]').val($(event.currentTarget).attr("data-index"));
                $(".Form--themes").submit();
            });
        });
    </script>

<?php }); ?>
