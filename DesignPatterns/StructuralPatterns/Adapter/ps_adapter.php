<?php

interface IDataAdapter {

    public function Fill($dataSet);
}

class DataRender {

    private IDataAdapter $dataAdapter;

    public function __construct(IDataAdapter $dataAdapter)
    {
        $this->dataAdapter = $dataAdapter;
    }

    public function render() {
        $this->dataAdapter->Fill("vbla");
    }
}

//concrete Adapter
class StubDBAdapter implements IDataAdapter {

    public function Fill($dataSet)
    {
        echo "The dataset has been filled: " . print_r($dataSet, true);
    }
}

//client code
$render = new DataRender(new StubDBAdapter());
$render->render();