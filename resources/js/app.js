import './bootstrap';
import '../../vendor/power-components/livewire-powergrid/resources/js/powergrid';

// Tom-Select for better multiselect
import TomSelect from 'tom-select';
window.TomSelect = TomSelect;
// Quill Editor : có tiền lệ gây lỗi 
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const Font = Quill.import('formats/font');

Font.whitelist = [
    'sans-serif',
    'serif',
    'monospace',
];

Quill.register(Font, true);

window.Quill = Quill;

