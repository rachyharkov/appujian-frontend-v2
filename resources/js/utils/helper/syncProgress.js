import axios from "axios";

export async function syncProgress(data) {
    // make api request to store data

    try {

        localStorage.setItem('progress', JSON.stringify(data.jawaban_murid));
        localStorage.setItem('id_murid', data.id_murid);
        localStorage.setItem('id_ujian', data.id_ujian);
        const response = await axios.post('/api/sync-progress', data);
        return response.data;
    } catch (error) {
        return error;
    }
}

export async function checkProgress(data) {
    try {
        const response = await axios.post('/api/check-progress', data);
        return response.data;
    } catch (error) {
        return error;
    }
}

export async function finishExam(data) {
    try {
        const response = await axios.post('/api/finish-exam', data);
        localStorage.removeItem('progress');
        localStorage.removeItem('id_murid');
        localStorage.removeItem('id_ujian');
        return response.data;
    } catch (error) {
        return error;
    }
}
