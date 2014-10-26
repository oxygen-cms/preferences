<?php

    use Oxygen\Core\Html\Form\EditableField;
    use Oxygen\Core\Html\Form\Footer;

    echo Form::open([
        'route' => [$blueprint->getRouteName('putUpdate'), $schema->getKey()],
        'method' => 'PUT',
        'class' => 'Form--sendAjax Form--warnBeforeExit Form--submitOnKeydown'
    ]);

    $titles = [];
?>

@foreach($schema->getFields() as $groupName => $groupItems)
    <div class="Block">
        @if($groupName !== '')
            <div class="Row">
                <h2 class="heading-beta">{{{ $groupName }}}</h2>
            </div>
        @endif
        @foreach($groupItems as $subgroupName => $subgroupItems)
             @if($subgroupName !== '')
                <div class="Row">
                    <h2 class="heading-gamma">{{{ $subgroupName }}}</h2>
                </div>
            @endif
            @foreach($subgroupItems as $field)
                <?php
                    $editable = new EditableField($field, $schema->getRepository()->get($field->name));
                    echo $editable->render();
                ?>
            @endforeach
        @endforeach
    </div>
@endforeach

<div class="Block">
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