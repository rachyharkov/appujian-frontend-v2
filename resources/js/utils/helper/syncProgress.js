import axios from "axios";

export async function syncProgress(data) {
    // make api request to store data

    try {
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
