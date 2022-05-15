<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User as UserModel;

class User extends Component
{
    public $data, $name, $email, $password, $selected_id;
    public $updateMode = false;

    public function render()
    {
        $this->data = UserModel::all();
        return view('livewire.user');
    }
    private function resetInput()
    {
        $this->name = null;
        $this->email = null;
        $this->password = null;
    }
    public function store()
    {
        $this->validate([
            'name' => 'required|min:5',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:6'
        ]);

        UserModel::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ]);
        $this->resetInput();
    }
    public function edit($id)
    {
        $record = UserModel::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->password = $record->password;
        $this->updateMode = true;
    }
    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:5',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:6'
        ]);
        if ($this->selected_id) {
            $record = UserModel::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password
            ]);
            $this->resetInput();
            $this->updateMode = false;
        }
    }
    public function destroy($id)
    {
        if ($id) {
            UserModel::destroy($id);
        }
    }
}
