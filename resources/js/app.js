import './bootstrap';
import '../../vendor/power-components/livewire-powergrid/resources/js/powergrid';
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

